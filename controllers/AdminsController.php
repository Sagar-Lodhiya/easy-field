<?php

namespace app\controllers;

use app\models\Admins;
use app\models\AdminsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminsController implements the CRUD actions for Admins model.
 */
class AdminsController extends Controller
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
                    'only' => ['create', 'update', 'delete', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Admins models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdminsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Admins model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Admins model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

        $model = new Admins();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->password = \Yii::$app->security->generatePasswordHash($model->password_hash);
                $request = $this->request->post();

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Admins model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load($this->request->post())) {
            if($model->password_hash !== ""){
                $model->password = \Yii::$app->security->generatePasswordHash($model->password_hash);
            }
            $request = $this->request->post();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Admins model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->identity->getId() == $id) {
            \Yii::$app->session->setFlash('error', 'Logged in admin can not be deleted');
        } else {

            $model = $this->findModel($id);
            $model->is_deleted = 1;
            $model->save(false);
            \Yii::$app->session->setFlash('success', 'Admin Successfully Deleted');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admins model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Admins the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admins::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
}
