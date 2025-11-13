<?php

namespace app\controllers;

use app\models\Users;
use app\models\UsersSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
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
                    'only' => ['create', 'update', 'delete', 'view', 'index', 'reset-device'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete', 'view', 'index', 'reset-device'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActiveUser(){
        $searchModel = new UsersSearch();

        // Fetch data for users who punched in today
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
    
        $query = \app\models\EmployeePunchDetails::find()
            ->where(['between', 'created_at', $todayStart, $todayEnd])
            ->with('user');
    
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
    
        return $this->render('active-user', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Users();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = 1;
        $model->save(false);
        \Yii::$app->session->setFlash('success', 'User Successfully Deleted');
        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $model = $this->findModel($id);
        $model->is_active = ($model->is_active == 0) ? 1 : 0;
        if ($model->save(false)) {
            return 1;
        } else {
            return json_encode($model->errors);
        }
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionResetDevice()
    {
        if (\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->post('id');

            if (!$id) {
                \Yii::$app->session->setFlash('error', 'No user ID provided.');
                return $this->redirect(['index']);
            }

            $model = Users::findOne($id);
            if (!$model) {
                \Yii::$app->session->setFlash('error', 'User not found.');
                return $this->redirect(['index']);
            }

            $model->device_id = null;
            if ($model->save(false)) {
                \Yii::$app->session->setFlash('success', 'Device reset successfully.');
            } else {
                \Yii::$app->session->setFlash('error', 'Failed to reset device.');
            }
        }

        return $this->redirect(['index']);
    }
}
