<?php

namespace app\controllers;

use app\helpers\TreeHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class TreeController extends \yii\web\Controller
{
    /**
     * @inheritDoc
     */
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
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $model = TreeHelper::getTreeData();
        return $this->render('index',[
            'model'=> json_encode($model)
        ]);
    }

}
