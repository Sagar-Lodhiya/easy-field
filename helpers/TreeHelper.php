<?php

namespace app\helpers;

use app\models\Admins;
use app\models\Users;
use Yii;

class TreeHelper
{
    public static function getTreeData()
    {
        $user = Admins::findOne(Yii::$app->user->identity->id);
        $id = array_column($user->descendants, 'id');
        $sponsor = Admins::findOne($user->parent_id);
        $sponsorName = $sponsor ? $sponsor->name : 'No Sponsor'; // Default message if no sponsor found
        // add conditions that should always apply here
        $query = Admins::find()
            ->where(['is_deleted' => 0])
            ->Where(['in', 'id', $id])
            ->all();

        $data = [];


        foreach ($query as $row) {
            $item = [
                'name' => $row->name,
                'type' => $row->type === 1 ? 'Super Admin' : ($row->type === 2 ? 'Admin' : 'Sub Admin'),
                // 'type' => $row->type,
                'tags' => [],
                'pid' => $row->parent_id,
                'id' => $row->id,
                'email' => $row->email,
                'Sponsor' => $sponsorName,
                'created_at' => $row->created_at,
            ];
            $data[] = $item;
        }

        $query2 = Users::find()
            ->where(['is_deleted' => 0])
            ->Where(['in', 'id', $id])
            ->all();

            foreach ($query2 as $row) {
                $item = [
                    'name' => $row->name,
                    'type' => 'Employee',
                    // 'type' => $row->type,
                    'tags' => [],
                    'pid' => $row->parent_id,
                    'id' => 'U'.$row->id,
                    'email' => $row->email,
                    'Sponsor' => $row->parent->name,
                    'created_at' => $row->created_at,
                ];
                $data[] = $item;
            }
    

        return $data;
    }
}
