<?php

namespace app\controllers;

use app\models\EmployeeGradeForm;
use app\models\EmployeeGrades;
use app\models\EmployeeGradesAmount;
use app\models\EmployeeGradesSearch;
use app\models\Grades;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeeGradesController implements the CRUD actions for EmployeeGrades model.
 */
class EmployeeGradesController extends Controller
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
     * Lists all EmployeeGrades models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeGradesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeeGrades model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = new EmployeeGradeForm();
        $model->loadData($id);
        $employeeGrade = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'employeeGrade' => $employeeGrade,
        ]);
    }

    /**
     * Creates a new EmployeeGrades model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new EmployeeGradeForm();
        if ($this->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $grades = new EmployeeGrades();
                $grades->name = $model->name;
                if ($grades->save()) {
                    foreach ($model['grades'] as $grade) {
                        // Check if categories key exists and is not empty
                        if (isset($grade['categories']) && is_array($grade['categories'])) {
                            foreach ($grade['categories'] as $category) {
                                $employeeGradeAmount = new EmployeeGradesAmount();
                                $employeeGradeAmount->grade_id = $grades->id;
                                $employeeGradeAmount->city_grade_id = $grade['type'];
                                $employeeGradeAmount->category_id = $category['category_id'];
                                $employeeGradeAmount->amount = $category['amount'];
                                if (!$employeeGradeAmount->save()) {
                                    print_r($employeeGradeAmount->errors);
                                    exit;
                                }
                            }
                        }
                    }
                }
                Yii::$app->session->setFlash('success', 'Data successfully saved!');
                
                return $this->redirect(['view', 'id' => $grades->id]);
            
            }
        }

        // Render the form
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing EmployeeGrades model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = new EmployeeGradeForm();
        $model->loadData($id);
        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $grades = $this->findModel($id);
            $grades->name = $model->name;
            
            if ($grades->save()) {

                EmployeeGradesAmount::deleteAll(['grade_id' => $grades->id]);

                foreach ($model['grades'] as $grade) {
                    // Check if categories key exists and is not empty
                    if (isset($grade['categories']) && is_array($grade['categories'])) {
                        foreach ($grade['categories'] as $category) {
                            $employeeGradeAmount = new EmployeeGradesAmount();
                            $employeeGradeAmount->grade_id = $grades->id;
                            $employeeGradeAmount->city_grade_id = $grade['type'];
                            $employeeGradeAmount->category_id = $category['category_id'];
                            $employeeGradeAmount->amount = $category['amount'];
                            if (!$employeeGradeAmount->save()) {
                                print_r($employeeGradeAmount->errors);
                                exit;
                            }
                        }
                    }
                }
            }
            return $this->redirect(['view', 'id' => $grades->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EmployeeGrades model.
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
        \Yii::$app->session->setFlash('success', 'Employee Grade Successfully Deleted');
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
     * Finds the EmployeeGrades model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EmployeeGrades the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmployeeGrades::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
