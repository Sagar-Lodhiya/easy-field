<?php

namespace app\controllers;

use app\models\Expenses;
use app\models\ExpensesSearch;
use app\models\ExpenseStatusForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpensesController implements the CRUD actions for Expenses model.
 */
class ExpensesController extends Controller
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
                    'only' => ['update', 'delete', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['update', 'delete', 'view'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Fix Expenses models.
     *
     * @return string
     */
    public function actionFixed()
    {
        $searchModel = new ExpensesSearch();
        $dataProvider = $searchModel->searchFixedExpense($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Fix Expenses models.
     *
     * @return string
     */
    public function actionClaimed()
    {
        $searchModel = new ExpensesSearch();
        $dataProvider = $searchModel->searchClaimedExpense($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expenses model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
{
    $expense = $this->findModel($id);

    
    $statusModel = new ExpenseStatusForm();
    $statusModel->setExpense($id); // Pass the expense ID to the form

    if ($this->request->isPost && $statusModel->load($this->request->post()) && $statusModel->validate() && $statusModel->store()) {
        \Yii::$app->session->setFlash('success', 'Expense Status Successfully Updated');
    } 

    return $this->render('view', [
        'model' => $expense,
        'statusModel' => $statusModel,
    ]);
}

    /**
     * Updates an existing Expenses model.
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
     * Finds the Expenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Expenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenses::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
