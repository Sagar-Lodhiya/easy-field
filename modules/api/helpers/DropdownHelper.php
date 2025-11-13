<?php

namespace app\modules\api\helpers;

use app\models\CityGrades;
use app\models\ExpenseCategories;
use app\models\LeaveType;
use app\models\Parties;
use app\models\PartyCategories;
use app\models\States;

class DropdownHelper
{
    public static function getParties()
    {
        $model = Parties::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    "id" => $item['id'],
                    "name" => (string) $item->dealer_name,
                ];
            },
            $model

        );
        return $result;
    }

    public static function getStates()
    {
        $model = States::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => (string) $item->name,
                ];
            },
            $model
        );
        return $result;
    }

    public static function getCities()
    {
        $model = CityGrades::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => (string) $item->city,
                ];
            },
            $model
        );
        return $result;
    }

    public static function getCategories()
    {
        $model = PartyCategories::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => (string) $item->name,
                ];
            },
            $model
        );
        return $result;
    }

    public static function getLeaveType()
    {
        $model = LeaveType::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => (string) $item->leave_type,
                ];
            },
            $model
        );
        return $result;
    }
   
    public static function getExpenseCategories()
    {
        $model = ExpenseCategories::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $result = array_map(
            function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => (string) $item->name,
                ];
            },
            $model
        );
        return $result;
    }

    public static function getAmountType()
    {
        $result = [
            [
                'id' => 1,
                'name' => 'Amount',
            ],
            [
                'id' => 2,
                'name' => 'Cheque',
            ]
        ];
        return $result;
    }

    public static function getCollectionOf()
    {
        $result = [
            [
                'id' => 1,
                'name' => 'Booking',
            ],
            [
                'id' => 2,
                'name' => 'Self',
            ],
            [
                'id' => 3,
                'name' => 'Cash Account',
            ]
        ];
        return $result;
    }
    
    public static function getPaymentDetails()
    {
        $result = [
            [
                'id' => 1,
                'name' => 'Handover',
            ],
            [
                'id' => 2,
                'name' => 'Deposited in Bank',
            ],
            [
                'id' => 3,
                'name' => 'Sales',
            ]
        ];
        return $result;
    }
}