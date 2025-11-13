<?php

namespace app\modules\api\helpers;

use app\models\EmployeePunchDetails;
use app\models\EmployeePunchLogs;
use app\models\Expenses;
use app\models\Leave;
use app\models\OffDays;
use app\models\Payment;
use app\models\Visits;
use Yii;

class AttendanceHelper
{
    public static function getAttendance($month, $year, $user_id)
    {
        $dt = date("F", mktime(0, 0, 0, $month, 10)) . ' ' . $year;
        $firstDay = date("Y-m-01", strtotime($dt));
        $lastDay = date("Y-m-t", strtotime($dt));

        // Fetch punch records
        $punchModel = EmployeePunchDetails::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['between', 'created_at', $firstDay, $lastDay])
            ->all();

        // Fetch leave records
        $leaves = Leave::find()
            ->where(['user_id' => $user_id, 'status' => 2])
            ->andWhere(['<=', 'start_date', $lastDay])
            ->andWhere(['>=', 'end_date', $firstDay])
            ->all();

        // Generate leave entries for each day in the leave range
        $leaveEntries = [];
        foreach ($leaves as $leave) {
            $currentDate = strtotime($leave->start_date);
            $endDate = strtotime($leave->end_date);

            while ($currentDate <= $endDate) {
                $leaveEntries[date('Y-m-d', $currentDate)] = [
                    'user_id' => $leave->user_id,
                    'created_at' => date('Y-m-d', $currentDate),
                    'day' => date('D', $currentDate),
                    'day_number' => date('d', $currentDate),
                    'type' => 'leave',
                    'name' => $leave->leaveType->leave_type,
                    'punch_in' => '',
                    'punch_out' => '',
                    'status' => (string)$leave->status,
                    'id' => (int)$leave->id
                ];
                $currentDate = strtotime('+1 day', $currentDate);
            }
        }

        // Generate punch entries
        $punchEntries = [];
        foreach ($punchModel as $punch) {
            $dateKey = date('Y-m-d', strtotime($punch->created_at));
            $dayKey = date('D', strtotime($punch->created_at));
            $dayNum = date('d', strtotime($punch->created_at));
            $punchEntries[$dateKey] = [
                'user_id' => $punch->user_id,
                'created_at' => $dateKey,
                'day' => $dayKey,
                'day_number' => $dayNum,
                'type' => 'punch',
                'name' => '',
                'status' => '',
                'id' => $punch->id,
                // 'punch_in' => date('H:i A', strtotime($punch->punchIn->created_at)),
                'punch_in' => $punch->punchIn 
                ? Yii::$app->formatter->asTime($punch->punchIn->created_at, 'php:H:i A') 
                : Yii::$app->formatter->asTime($punch->created_at, 'php:H:i A'),
                'punch_out' => $punch->punchOut ? Yii::$app->formatter->asTime($punch->punchOut->created_at, 'php:H:i A') : '',
            ];
        }

        // Get Holiday Entry

        $holidayEntries = self::getHolidayList($firstDay, $lastDay, $user_id);

        // Fill missing days with "absent"
        $attendance = [];
        $currentDate = strtotime($firstDay);

        $workingDayCount = $publicHolidayCount = $leaveCount = $absentCount = 0;

        while ($currentDate <= strtotime($lastDay)) {
            $dateKey = date('Y-m-d', $currentDate);

            if (isset($punchEntries[$dateKey])) {
                $attendance[] = $punchEntries[$dateKey]; // Add punch data
                $workingDayCount += 1;
            } elseif (isset($leaveEntries[$dateKey])) {
                $attendance[] = $leaveEntries[$dateKey]; // Add leave data
                $leaveCount += 1;
            } elseif (isset($holidayEntries[$dateKey])) {
                $attendance[] = $holidayEntries[$dateKey]; // Add holiday data
                $publicHolidayCount += 1;
            } else {
                $dayKey = date('D', strtotime($dateKey));
                $dayNum = date('d', strtotime($dateKey));

                $type = (strtotime($dateKey) > strtotime(date('Y-m-d'))) ? '' : 'absent';

                $attendance[] = [
                    'user_id' => $user_id,
                    'created_at' => $dateKey,
                    'type' => $type,
                    'day' => $dayKey,
                    'day_number' => $dayNum,
                    'name' => '',
                    'punch_in' => '',
                    'punch_out' => '',
                    'status' => '',
                    'id' => 0
                ];
                $absentCount += 1;
            }
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return [
            'analytics' => [
                'working_days' => (string)$workingDayCount,
                'public_holiday' => (string)$publicHolidayCount,
                'leaves' => (string)$leaveCount,
                'absent' => (string)$absentCount
            ],
            'attendance' => $attendance
        ];
    }

    public static function getHolidayList($start_date, $end_date, $user_id = 2)
    {
        $model = OffDays::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();

        $holidayEntries = [];
        foreach ($model as $holiday) {
            $holidayDate = strtotime($holiday->date);
            $dayOfWeek = date('w', $holidayDate); // 0 = Sunday, 6 = Saturday
            $monthDay = date('d', $holidayDate);
            $yearMonthDay = date('m-d', $holidayDate);

            for ($day = strtotime($start_date); $day <= strtotime($end_date); $day = strtotime("+1 day", $day)) {
                $dateKey = date('Y-m-d', $day);

                switch ($holiday->type) {
                    case 1: // Weekly (Same day of the week)
                        if (date('w', $day) == $dayOfWeek) {
                            $holidayEntries[$dateKey] = [
                                'user_id' => $user_id,
                                'created_at' => $dateKey,
                                'day' => date('D', $day),
                                'type' => 'public_holiday',
                                'title' => $holiday->title,
                                'punch_in' => '',
                                'punch_out' => ''
                            ];
                        }
                        break;
                    case 2: // Monthly (Same day of the month)
                        if (date('d', $day) == $monthDay) {
                            $holidayEntries[$dateKey] = [
                                'user_id' => $user_id,
                                'created_at' => $dateKey,
                                'day' => date('D', $day),
                                'type' => 'public_holiday',
                                'title' => $holiday->title,
                                'punch_in' => '',
                                'punch_out' => ''
                            ];
                        }
                        break;
                    case 3: // Yearly (Same day and month every year)
                        if (date('m-d', $day) == $yearMonthDay) {
                            $holidayEntries[$dateKey] = [
                                'user_id' => $user_id,
                                'created_at' => $dateKey,
                                'day' => date('D', $day),
                                'type' => 'public_holiday',
                                'title' => $holiday->title,
                                'punch_in' => '',
                                'punch_out' => ''
                            ];
                        }
                        break;
                    case 4: // Once-off (Exact date match)
                        if ($dateKey == $holiday->date) {
                            $holidayEntries[$dateKey] = [
                                'user_id' => $user_id,
                                'created_at' => $dateKey,
                                'day' => date('D', $day),
                                'type' => 'public_holiday',
                                'title' => $holiday->title,
                                'punch_in' => '',
                                'punch_out' => ''
                            ];
                        }
                        break;
                }
            }
        }

        return $holidayEntries;
    }

    public static function getAttendanceDetails($id)
    {


        $punchDetails = EmployeePunchDetails::findOne($id);

        $currentDate = date('Y-m-d', strtotime($punchDetails->created_at));
        $analytics = [
            'punch_in_time' => date('h:i A', strtotime($punchDetails->punchIn->created_at)),
            'punch_in_date' => date('Y-m-d', strtotime($punchDetails->punchIn->created_at)),

            'total_hours' => $punchDetails->punch_out_id
                ? gmdate("H:i", strtotime($punchDetails->punchOut->created_at) - strtotime($punchDetails->punchIn->created_at))
                : '00:00',

            'punch_out_time' => $punchDetails->punch_out_id ? date('h:i A', strtotime($punchDetails->punchOut->created_at)) : '',
            'punch_out_date' => $punchDetails->punch_out_id ? date('Y-m-d', strtotime($punchDetails->punchOut->created_at)) : '',
            'punch_out_place' => $punchDetails->punch_out_place,
            'punch_out_image' => $punchDetails->punch_out_image,
            'punch_in_place' => $punchDetails->punch_in_place,
            'punch_in_image' => $punchDetails->punch_in_image,
            'vehicle_type' => $punchDetails->vehicle_type,
            'tour_details' => $punchDetails->tour_details,
            'traveled_km' => $punchDetails->traveled_km ? (string)$punchDetails->traveled_km : "",
            'total_distance' => $punchDetails->total_distance ? (string)$punchDetails->total_distance : "",
            'da_amount' => $punchDetails->da ? (string) $punchDetails->da : "0",
            'ta_amount' => $punchDetails->ta ? (string) $punchDetails->ta : "0",
            'created_at' => date('d/m/Y', strtotime($punchDetails->created_at))

        ];

        $isFirst = true;

        $expense = array_map(function ($expense) use ($isFirst) {

            $data =  [
                'type' => 'expense',
                'is_first' => $isFirst,
                'expense_details' => $expense->expense_details,
                'type' => $expense->expense_type,
                'category_id' => $expense->category_id,
                'category_name' => $expense->category_id ? $expense->category->name : '',
                'status_id' => $expense->status_id,
                'status_name' => $expense->status->name,
                'requested_amount' => $expense->requested_amount,
                'approved_amount' => $expense->approved_amount,
                'city_id' => $expense->city_id ?? '',
                'city_name' => $expense->city_id ? $expense->city->city : '',
                'expense_photo' => $expense->expense_photo,
                'created_at' => date('d/m/Y', strtotime($expense->created_at))
            ];

            $isFirst = false;

            return $data;
        }, Expenses::find()->where(['expense_type' => 'claimed', 'user_id' => Yii::$app->user->identity->id, 'DATE(created_at)' => $currentDate])->all());

        $isFirst = true;

        $visit = array_map(function ($visit) use ($isFirst) {
            $data = [
                'type' => 'visit',
                'is_first' => $isFirst,
                'id' => $visit->id,
                'created_at' => $visit->created_at,
                'time' => $visit->time,
                'place' => $visit->place,
                'party_id' => $visit->party_id,
                'party_name' => $visit->party_id ? $visit->party->firm_name : '',
                'duration' => $visit->duration,
                'discussion_point' => $visit->discussion_point,
                'remarks' => $visit->remark,
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,
            ];

            $isFirst = false;
            return $data;
        }, Visits::find()->where(['user_id' => Yii::$app->user->identity->id, 'DATE(created_at)' => $currentDate])->all());

        $isFirst = false;


        $payment = array_map(function ($payments) use ($isFirst) {
            $data = [
                'type' => 'payment',
                'is_first' => $isFirst,
                'id' => $payments->id,
                'party_id' => $payments->party_id,
                'party_name' => $payments->parties->dealer_name,
                'amount' => $payments->amount,
                'amount_type' => $payments->amount_type,
                'amount_details' => $payments->amount_details,
                'collection_of' => $payments->collection_of,
                'payments_details' => $payments->payments_details ? $payments->payments_details : '',
                'extra' => $payments->extra,
                'status_id' => $payments->status,
                'status_name' => ($payments->status == 1) ?  'Pending' : (($payments->status == 2) ? 'Approved' : 'Declined'),
                'payments_photo' => $payments->payments_photo,
                'created_at' => $payments->created_at,
            ];

            $isFirst = false;

            return $data;
        }, Payment::find()->where(['user_id' => Yii::$app->user->identity->id, 'DATE(created_at)' => $currentDate])->all());


        return [
            'analytics' => $analytics,
            'activity' => array_merge($expense, $visit, $payment),
        ];
    }
}