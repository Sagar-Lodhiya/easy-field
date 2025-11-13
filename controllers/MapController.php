<?php

namespace app\controllers;

use app\models\EmployeePunchDetails;
use yii\web\Response;

class MapController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetPins()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        // Example pin data
        $pins = [
            ['id' => 1, 'lat' => 41.85, 'lng' => -87.65, 'info' => 'Pin 1 Details'],
            ['id' => 2, 'lat' => 42.00, 'lng' => -87.90, 'info' => 'Pin 2 Details'],
        ];
        $pins = EmployeePunchDetails::getActiveEmployees();
        return json_encode($pins);
    }

}
