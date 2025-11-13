<?php

namespace app\controllers;

use app\models\Users;
use app\models\UsersSearch;
use app\models\AttendanceSearch;

use app\models\EmployeePunchDetails;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class AttendanceController extends Controller
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
                    'only' => ['active-user', 'absent-user', 'detail', 'report'],
                    'rules' => [
                        [
                            'actions' => ['active-user', 'absent-user', 'detail', 'report'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

   /**
     * Display active users (punched in today).
     *
     * @return string
     */
    public function actionActiveUser()
    {
        $searchModel = new AttendanceSearch();
        $dataProvider = $searchModel->searchActiveUsers(Yii::$app->request->queryParams);
        
        // Pass `$dataProvider` to a GridView or another widget in the view
        

        return $this->render('active-user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Display absent users (not punched in today).
     *
     * @return string
     */
    public function actionAbsentUser()
    {
        $searchModel = new AttendanceSearch();
        $dataProvider = $searchModel->searchAbsentUsers(\Yii::$app->request->queryParams);

        return $this->render('absent-user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport()
    {
        $searchModel = new \app\models\AttendanceSearch();
        $dataProvider = $searchModel->searchAttendanceUsers(\Yii::$app->request->queryParams);

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeePunchDetails model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the EmployeePunchDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EmployeePunchDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmployeePunchDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
