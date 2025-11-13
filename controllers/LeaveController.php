<?php

namespace app\controllers;

use app\models\Leave;
use app\models\LeavesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\filters\AccessControl;

/**
 * LeaveController implements the CRUD actions for Leave model.
 */
class LeaveController extends Controller
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
                    ],],
                    'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index', 'create', 'update', 'delete', 'view', 'update-status'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view', 'update-status'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],

            ]
        );
    }

    /**
     * Lists all Leave models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LeavesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Leave model.
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
     * Creates a new Leave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Leave();
    
        if ($this->request->isPost) {
            // Load POST data into the model
            if ($model->load($this->request->post())) {
                // Assign the logged-in user's ID
                $model->user_id = Yii::$app->user->id;
             
                // Save the record and redirect if successful
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /**
     * Updates an existing Leave model.
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
     * Deletes an existing Leave model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Leave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Leave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Leave::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateStatus()
{
    $request = Yii::$app->request->post();
    $model = $this->findModel($request['Leave']['id']);

    if ($model->status == 1) { // Process only if status is Pending
        if ($model->load($request)) {
            

            if (empty($model->status)) {
                Yii::$app->session->setFlash('warning', 'Please select a leave status.');
                return $this->redirect(['view', 'id' => $model->id]);
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!$model->save()) {
                    throw new \Exception('Failed to update Leave Request: ' . json_encode($model->errors));
                }

                $message = ($model->status == 2)
                    ? 'Leave request successfully Approved.'
                    : 'Leave request successfully Declined.';
                
                Yii::$app->session->setFlash('success', $message);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
    }

    return $this->redirect(['view', 'id' => $model->id]);
}
}
