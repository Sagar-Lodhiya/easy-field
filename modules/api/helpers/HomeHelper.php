<?php

namespace app\modules\api\helpers;

use app\models\AttendanceSearch;
use app\models\EmployeePunchDetails;
use app\models\Expenses;
use app\models\Leave;
use app\models\Parties;
use app\models\Payment;
use app\models\Users;
use app\models\Visits;
use Yii;

class HomeHelper
{
    public static function getUser()
    {
        $currentDate = date('Y-m-d H:i:s');
        $model = Users::find()->where(['id' => Yii::$app->user->identity->id, 'is_active' => 1, 'is_deleted' => 0])->one();
        
        if (!$model) {
            return null;
        }
        
        $userType = 0;  
        
        if($model->user_type == 'sales_and_office'){
            $userType = 3;
        }elseif($model->user_type == 'sales'){
            $userType = 1;
        }elseif($model->user_type == 'office'){
            $userType = 2;
        }
        
        return [
            "name" => $model->name,
            "date" => $currentDate,
            "profile" => $model->profile,
            "user_type" => $userType,
        ];
    }

    public static function getMenu()
    {

        $totalParties = Parties::find()
            ->where(['is_active' => 2, 'employee_id' => Yii::$app->user->identity->id])
            ->count() ?? 0;

        $totalVisits = Visits::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->count() ?? 0;

        $totalPayments = Payment::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->sum('amount') ?? 0;

        $totalExpenses = Expenses::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->sum('requested_amount') ?? 0;

        return [
            "expense" => $totalExpenses,
            "visit" => $totalVisits,
            "payment" => $totalPayments,
            "parties" => $totalParties,
        ];
    }

    public static function getAttendance()
    {
        $currentDate = date('Y-m-d');
        $is_on_leave = false;
        $has_punch_in = false;
        $can_punch_in = false;
        $has_punch_out = false;
        $can_punch_out = false;
        $punch_in_time = '';
        $punch_out_time = '';
        $can_start_tracking = false;
        $can_stop_tracking = false;
        $is_tracking_started = false;
        $has_stop_tracking = false;
        // Get user details to check user_type
        $user = Users::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $userType = $user ? $user->user_type : null;

        // // Check if the user is on leave
        // $leave = Leave::find()->where(['user_id' => Yii::$app->user->identity->id, 'status' => 2])
        //     ->andWhere(['<=', 'start_date', $currentDate]) // Start date is on or before the current date
        //     ->andWhere(['>=', 'end_date', $currentDate])   // End date is on or after the current date
        //     ->one();
        // if ($leave) {
        //     $is_on_leave = true;
        // }

        // Check if the user has punched in and out
        $punchDetails = EmployeePunchDetails::find()->where(['user_id' => Yii::$app->user->identity->id, 'DATE(created_at)' => $currentDate])->orderBy(['id' => SORT_DESC])->one();
        // $punchDetails = EmployeePunchDetails::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['id' => SORT_DESC])->one();
        $punch_type = $punchDetails ? $punchDetails->punch_type : null;
        if ($punchDetails) {
            if ($punchDetails->punch_in_id) {
                $has_punch_in = true;
                // Check if punchIn relationship exists before accessing created_at
                if ($punchDetails->punchIn) {
                    $punch_in_time = Yii::$app->formatter->asTime($punchDetails->punchIn->created_at, 'php:H:i A');
                } else {
                    // For office users without punch logs, use the punch details created_at
                    $punch_in_time = Yii::$app->formatter->asTime($punchDetails->created_at, 'php:H:i A');
                }
                $punch_id = $punchDetails->id;
            }
            if ($punchDetails->punch_out_id) {
                $has_punch_out = true;
                // Check if punchOut relationship exists before accessing created_at
                if ($punchDetails->punchOut) {
                    $punch_out_time = Yii::$app->formatter->asTime($punchDetails->punchOut->created_at, 'php:H:i A');
                } else {
                    // For office users without punch logs, use the punch details created_at
                    $punch_out_time = Yii::$app->formatter->asTime($punchDetails->created_at, 'php:H:i A');
                }
            }
            if($punchDetails->punch_out_place){
                $has_stop_tracking = true;
            }
        }

        $pendingPunch = EmployeePunchDetails::find()->where(['user_id' => Yii::$app->user->identity->id, 'punch_out_id' => null])->one();
        if ($pendingPunch) {
            // print_r($pendingPunch);exit;
            $has_punch_in = true;
            // Check if punchIn relationship exists before accessing created_at
            if ($pendingPunch->punchIn) {
                $punch_in_time = Yii::$app->formatter->asTime($pendingPunch->punchIn->created_at, 'php:H:i A');
            } else {
                // For office users without punch logs, use the punch details created_at
                $punch_in_time = Yii::$app->formatter->asTime($pendingPunch->created_at, 'php:H:i A');
            }
            $punch_id = $pendingPunch->id;
        }
        
        // // Determine if the user can punch in or out
        $can_punch_in = !$has_punch_in && !$is_on_leave;
        $can_punch_out = $has_punch_in && !$has_punch_out && !$is_on_leave;
        $punch_in_id = $has_punch_in ? $punch_id : 0;

        // Determine can_start_tracking and can_stop_tracking
        // can_start_tracking: true only if user is punched in AND user_type='sales_and_office' AND punch_type='O', otherwise false
        if ($has_punch_in && $userType == 'sales_and_office') {
            // Use pendingPunch if available, otherwise use punchDetails
            $currentPunch = $pendingPunch ?: $punchDetails;
            
            if ($currentPunch && $currentPunch->punch_type == 'O') {
                // Check if tracking has already started (has vehicle_type and punch_in_place)
                if ($currentPunch->vehicle_type && $currentPunch->punch_in_place) {
                    $is_tracking_started = true;
                    $can_start_tracking = false; // Can't start again if already started
                } else {
                    $can_start_tracking = true;
                }
            } else {
                $can_start_tracking = false;
            }
        } else {
            $can_start_tracking = false;
        }

        // can_stop_tracking: default false, only true when user is punched in
        $can_stop_tracking = $has_punch_in && !$has_punch_out && $userType == 'sales_and_office' && $punch_type == 'O' && !$has_stop_tracking;

        return [
            'is_on_leave' => $is_on_leave,
            'can_punch_in' => $can_punch_in, 
            'punch_in_time' => $punch_in_time,
            'can_punch_out' => $can_punch_out ,
            'punch_out_time' =>$punch_out_time, 
            'punch_in_id' => $punch_in_id,
            'can_start_tracking' => $can_start_tracking,
            'can_stop_tracking' => $can_stop_tracking,
            'is_tracking_started' => $is_tracking_started,
            'punch_type' => $punch_type,
        ];
    }

    public static function getAnalytics()
    {
        $visits = Visits::find()->where(['user_id' => Yii::$app->user->identity->id])->count();
        $pendingExpense = Expenses::find()
            ->where(['user_id' => Yii::$app->user->identity->id, 'status_id' => 1])
            ->sum('requested_amount') ?: 0;

        $dt = date("F", mktime(0, 0, 0, date('n'), 10)) . ' ' . date('Y');
        $firstDay = date("Y-m-01", strtotime($dt));
        $lastDay = date("Y-m-t", strtotime($dt));

        // Fetch punch records
        $punchCount = EmployeePunchDetails::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->andWhere(['between', 'created_at', $firstDay, $lastDay])
            ->count();
        
            $traveledKm = EmployeePunchDetails::find()
            ->where(['user_id' => Yii::$app->user->identity->id])
            ->andWhere(['between', 'created_at', $firstDay, $lastDay])
            ->sum('traveled_km');

        return [
            [
                'title' => 'Pending Expense',
                'value' => $pendingExpense
            ],
            [
                'title' => 'Total Visits',
                'value' => (string)$visits
            ],
            [
                'title' => 'Km Traveled',
                'value' => (string)$traveledKm
            ],
            [
                'title' => 'Working Days',
                'value' => (string)$punchCount
            ],
        ];
    }
}