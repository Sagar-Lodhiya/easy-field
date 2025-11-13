<?php

namespace app\controllers;

use app\models\Payment;
use app\models\PaymentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PaymentsController implements the CRUD actions for Payments model.
 */
class PaymentsController extends Controller
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
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['create', 'update', 'delete', 'view', 'index', 'approve', 'decline', 'update-status'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete', 'view', 'index', 'approve', 'decline', 'update-status'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                
            ]
        );
    }

    /**
     * Lists all Payments models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payments model.
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
     * Creates a new Payments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Payment();

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
     * Updates an existing Payments model.
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
     * Deletes an existing Payments model.
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
     * Finds the Payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateStatus()
    {
        $request = Yii::$app->request->post();
        $model = $this->findModel($request['Payment']['id']);

        if ($model->status == 1) { // Process only if status is Pending
            if ($model->load($request)) {


                if (empty($model->status)) {
                    Yii::$app->session->setFlash('warning', 'Please select a payment status.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$model->save()) {
                        throw new \Exception('Failed to update Payment Request: ' . json_encode($model->errors));
                    }

                    $message = ($model->status == 2)
                        ? 'Payment request successfully Approved.'
                        : 'Payment request successfully Declined.';

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

    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        if ($model->status == 1) {
            $model->status = 2;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Payment request successfully Approved.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to approve payment request: ' . json_encode($model->errors));
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Payment request cannot be approved. Current status is not Pending.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDecline($id)
    {
        $model = $this->findModel($id);

        if ($model->status == 1) {
            $model->status = 3;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Payment request successfully Declined.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to decline payment request: ' . json_encode($model->errors));
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Payment request cannot be declined. Current status is not Pending.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
