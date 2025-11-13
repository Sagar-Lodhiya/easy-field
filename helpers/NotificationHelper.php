<?php

namespace app\helpers;

use app\models\Users;
use Yii;

class NotificationHelper
{


    public static function sendPush($message, $devices, $target = "", $id = "", $push_title = "", $data_title = "", $shouldStore = true)
    {
        $push_title = !empty($push_title) ? $push_title : Yii::$app->params['eventSubscriberName']; // push prompt title
        $data_title = !empty($data_title) ? $data_title : $message; // only for ios push

        if(count($devices) == 0 ){
            $users = Users::find()->where(['is_deleted' => 0, 'is_active' => 1])->all();
        } else {
            $users = Users::find()->where(['access_token' => $devices[0]])->all();
        }

        try {
            // Prepare the payload for FCM HTTP v1
            if (count($devices) == 0) {
                
                $payload = [
                    "message" => [
                        "token" => "cpd87_-3Tre6DuUtr5Ubii:APA91bEGrsJNgIyn1ubO-7T3dSS0aJEzT85RAQHf6PH4k8YuSk8DW6q_mMyVatwm56j27-xlEUp7Y2mHmf9_NghczzwAjozQgOMWgM_0RuXBTkPDIjXZ1TQ", // Replace with your topic or token
                        "topic" => Yii::$app->params['eventSubscriberName'], // Replace with your topic or token
                        "notification" => [
                            "title" => $push_title,
                            "body" => $message,
                        ],
                        // "token" => $devices[0],
                        "data" => [
                            "target" =>  (string)$target,
                            "target_id" => (string)$id,
                            "data_title" => $data_title
                        ]
                    ],
                ];
            } else {

                $payload = [
                    "message" => [
                        "token" => $devices[0],
                        "notification" => [
                            "title" => $push_title,
                            "body" => $message,
                        ],
                        "data" => [
                            "target" =>  (string)$target,
                            "target_id" => (string)$id,
                            "data_title" => $data_title
                        ]
                    ]
                ];
            }

            // print_r($payload);exit;
            
            // Send the notification
            $response = Yii::$app->notification->sendNotification($payload);
            // print_r($response);exit;
            if($shouldStore){
                self::storeNotification($users, $data_title);
            }
            


            return $response;
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public static function storeNotification($users, $text){
        
        $data = array_map(function($user) use($text) {
            return [
                'user_id' => $user->id,
            'admin_id' => Yii::$app->user->identity->id,
            'text' => $text
            ];
        }, $users);

        Yii::$app->db->createCommand()->batchInsert('notifications', ['user_id', 'admin_id', 'text'], $data)->execute();
        
    }
}
