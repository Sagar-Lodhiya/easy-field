<?php

namespace app\helpers;

use app\models\EmployeePunchDetails;
use app\models\Expenses;
use app\models\Parties;
use app\models\Users;
use app\models\Visits;
use app\models\Payment;
use yii\db\Expression;

class DashboardHelper
{

    public static function getDashboardData()
    {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');

        // Count active users: Users who punched in today and haven't punched out
        $activeUsers = EmployeePunchDetails::find()
            ->where(['between', 'created_at', $todayStart, $todayEnd])
            ->andWhere(['punch_out_id' => null])
            ->count();

        // Fetch IDs of users who are active today
        $activeUserIds = EmployeePunchDetails::find()
            ->select('user_id')
            ->distinct()
            ->where(['between', 'created_at', $todayStart, $todayEnd])
            ->column();

        // Count absent users: Users not in the active user list
        $absentUsers = Users::find()
            ->where(['not in', 'id', $activeUserIds])
            ->count();

        $pendingExpense = Expenses::find()
            ->where(['status_id' => 1])
            ->count();

        $totalParties = Parties::find()
            ->where(['is_active' => 2])
            ->count();

        $totalUsers = Users::find()
            ->where(['is_active' => 1])
            ->count();

        $pendingExpenseAmount = Expenses::find()
            ->where(['status_id' => 1])
            ->sum('requested_amount');

        $totalVisits = Visits::find()->count();

        $totalPayments = Payment::find()
            ->sum('amount');

        // Fetch expense details with category, city, and status mapping        
        $expenseDetails = Expenses::find()
            ->select([
                'e.expense_details',
                'e.requested_amount',
                'cg.city AS city_name', // Join with city_grades table
                'ec.name AS category_name', // Join with expense_categories table
                new Expression(
                    "CASE 
                        WHEN e.status_id = 1 THEN 'Requested' 
                        WHEN e.status_id = 2 THEN 'Pending' 
                        WHEN e.status_id = 3 THEN 'Partially Approved' 
                        WHEN e.status_id = 4 THEN 'Approved' 
                        WHEN e.status_id = 5 THEN 'Rejected' 
                        ELSE 'Unknown' 
                    END AS status"
                ),
            ])
            ->alias('e') // Alias for the Expenses table
            ->leftJoin('city_grades cg', 'e.city_id = cg.id') // Join with city_grades
            ->leftJoin('expense_categories ec', 'e.category_id = ec.id') // Join with expense_categories
            ->where(['between', 'e.created_at', $todayStart, $todayEnd]) // Filter for today's records
            ->orderBy(['e.created_at' => SORT_DESC]) // Sort by latest first
            ->asArray()
            ->all();
        


        return [
            'activeUsers' => $activeUsers,
            'absentUsers' => $absentUsers,
            'pendingExpense' => $pendingExpense,
            'totalParties' => $totalParties,
            'totalUsers' => $totalUsers,
            'pendingExpenseAmount' => $pendingExpenseAmount,
            'totalVisits' => $totalVisits,
            'totalPayments' => $totalPayments,
            'expenseDetails' => $expenseDetails,
        ];
    }

}
