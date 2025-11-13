<?php

namespace app\controllers;

use app\models\FileUploadForm;
use app\models\Parties;
use app\models\PartiesSearch;
use app\models\PartyCategories;
use app\models\Users;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;

/**
 * PartiesController implements the CRUD actions for Parties model.
 */
class PartiesController extends Controller
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
     * Lists all Parties models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PartiesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Parties model.
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
     * Creates a new Parties model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Parties();

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
     * Updates an existing Parties model.
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
     * Deletes an existing Parties model.
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
        \Yii::$app->session->setFlash('success', 'Party Successfully Deleted');

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
     * Finds the Parties model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Parties the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Parties::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport()
{
    ini_set('memory_limit', -1);

    // Start output buffering
    ob_clean();

    $searchModel = new PartiesSearch();
    $dataProvider = $searchModel->exportSearch($this->request->queryParams);
    $searchModel = $dataProvider->allModels;

    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    $activeWorksheet->setCellValue('A1', 'Id');
    $activeWorksheet->setCellValue('B1', 'Employee Name');
    $activeWorksheet->setCellValue('C1', 'Firm Name');
    $activeWorksheet->setCellValue('D1', 'Dealer Name');
    $activeWorksheet->setCellValue('E1', 'Party Category');
    $activeWorksheet->setCellValue('F1', 'Dealer Phone');
    $activeWorksheet->setCellValue('G1', 'City Or Town');
    $activeWorksheet->setCellValue('H1', 'Address');
    $activeWorksheet->setCellValue('I1', 'GST No');
    $activeWorksheet->setCellValue('J1', 'Dealer Aadhar');
    $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E90FF');

    $n = 2;
    foreach ($searchModel as $row) {
        $activeWorksheet->setCellValue('A' . $n, base64_encode($row->id));
        $activeWorksheet->setCellValue('B' . $n, $row->employee->name);
        $activeWorksheet->setCellValue('C' . $n, $row->firm_name);
        $activeWorksheet->setCellValue('D' . $n, $row->dealer_name);
        $activeWorksheet->setCellValue('E' . $n, ($row->partyCategory) ? $row->partyCategory->name : "");
        $activeWorksheet->setCellValue('F' . $n, $row->dealer_phone);
        $activeWorksheet->setCellValue('G' . $n, $row->city_or_town);
        $activeWorksheet->setCellValue('H' . $n, $row->address);
        $activeWorksheet->setCellValue('I' . $n, $row->gst_number);
        $activeWorksheet->setCellValue('J' . $n, $row->dealer_aadhar);
        $n++;
    }

    foreach (range('A', 'J') as $columnID) {
        $activeWorksheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    
    // Clean any previous output
    ob_clean();

    // Set headers for download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="parties-list-' . date('YmdHis') . '.xlsx"');
    header('Cache-Control: max-age=0');
    try {
        $objWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $objWriter->save('php://output');
        exit();
    } catch (\Exception $e) {
        print_r("Export failed: " . $e->getMessage());exit;
        // exit();
    }
    // Exit to prevent further output
}


    // public function actionUpload()
    // {
    //     $model = new FileUploadForm();

    //     $uploadedIds = [];

    //     if ($this->request->isPost) {
    //         if (!empty($_FILES['FileUploadForm']['name']['file'])) {
    //             // print_r($_FILES['FileUploadForm']['tmp_name']['file']);exit;
    //             try {

    //                 $inputFileType = IOFactory::identify($_FILES['FileUploadForm']['tmp_name']['file']);
    //                 $objReader = IOFactory::createReader($inputFileType);
    //                 $objPHPExcel = $objReader->load($_FILES['FileUploadForm']['tmp_name']['file']);
    //             } catch (\Exception $e) {
    //                 Yii::$app->session->setFlash('error', $e);
    //                 return $this->redirect(['index']);
    //             }
    //             $sheet = $objPHPExcel->getSheet(0);
    //             $highestRow = $sheet->getHighestRow();
    //             $highestColumn = $sheet->getHighestColumn();
    //             for ($row = 2; $row <= $highestRow; $row++) {
    //                 $rowData = $sheet->rangeToArray('A' . $row . ":" . $highestColumn . $row, NUll, True, false);

    //                 $employee = '';

    //                 if ($rowData[0][1]) {
    //                     $employeeModel = Users::findOne(['name' => $rowData[0][1]]);
    //                     if ($employeeModel !== NULL) {
    //                         $employee = $employeeModel->id;
    //                     }
    //                 }

    //                 $partyCategory = '';

    //                 if ($rowData[0][1]) {
    //                     $partyCategoryModel = PartyCategories::findOne(['name' => $rowData[0][1]]);
    //                     if ($employeeModel !== NULL) {
    //                         $partyCategory = $partyCategoryModel->id;
    //                     }
    //                 }

    //                 $partyModel = NULL;

    //                 if (!empty(trim($rowData[0][0]))) {

    //                     $partyModel = Parties::findOne(['id' => base64_decode($rowData[0][0]), 'company_id' => $this->company_id]);

    //                     $uploadedIds[] = base64_decode($rowData[0][0]);

    //                     if ($partyModel !== NULL) {

    //                         $partyModel->employee_id = $employee;
    //                         $partyModel->firm_name = $rowData[0][2];
    //                         $partyModel->dealer_name = (string)$rowData[0][3];
    //                         $partyModel->party_category_id = $partyCategory;
    //                         $partyModel->dealer_phone = $rowData[0][5];
    //                         $partyModel->city_or_town = (string)$rowData[0][6];
    //                         $partyModel->address = (string)$rowData[0][7];
    //                         $partyModel->email = (string)$rowData[0][8];
    //                         $partyModel->gst_number = (string)$rowData[0][9];
    //                         $partyModel->gst_no = (string)$rowData[0][10];
    //                         $partyModel->dealer_aadhar = (string)$rowData[0][11];
    //                         if (!$partyModel->save()) {

    //                             print_r($partyModel->errors);
    //                             exit;
    //                         } else {

    //                             $uploadedIds[] = $partyModel->id;
    //                         }
    //                     }
    //                 } else {

    //                     $partyModel = new Parties();

    //                     $partyModel->employee_id = $employee;
    //                     $partyModel->firm_name = $rowData[0][2];
    //                     $partyModel->dealer_name = (string)$rowData[0][3];
    //                     $partyModel->party_category_id = $partyCategory;
    //                     $partyModel->dealer_phone = $rowData[0][5];
    //                     $partyModel->city_or_town = (string)$rowData[0][6];
    //                     $partyModel->address = (string)$rowData[0][7];
    //                     $partyModel->email = (string)$rowData[0][8];
    //                     $partyModel->gst_no = (string)$rowData[0][9];
    //                     $partyModel->gst_no = (string)$rowData[0][10];
    //                     $partyModel->dealer_aadhar = (string)$rowData[0][11];

    //                     if (!$partyModel->save()) {

    //                         print_r($partyModel->errors);
    //                         exit;
    //                     } else {

    //                         $uploadedIds[] = $partyModel->id;
    //                     }
    //                 }
    //             }

    //             Parties::updateAll(['is_deleted' => 1], ['not in', 'id', $uploadedIds]);


    //             Yii::$app->session->setFlash('success', 'import Successfully completed');
    //             return $this->redirect(['index']);
    //         }
    //     }

    //     return $this->render('upload', [
    //         'model' => $model
    //     ]);
    // }
}
