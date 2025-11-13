<?php

namespace app\controllers;

use app\models\SystemSettingForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SettingController extends \yii\web\Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                            'delete' => ['POST'],
                        ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['application', 'system'],
                    'rules' => [
                            [
                                'actions' => ['application', 'system'],
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        ],
                ],
            ]
        );
    }
    public function actionApplication()
    {
        return $this->render('application');
    }

    public function actionSystem()
    {
        $model = new SystemSettingForm();
        $model->loadSetting();
        if ($model->load($this->request->post()) && $model->validate() && $model->store()) {
            Yii::$app->session->setFlash(
                'success',
                'Setting updated successfully',
            );
            return $this->redirect('system');
        }
        return $this->render('system', [
            'model' => $model
        ]);
    }

}
