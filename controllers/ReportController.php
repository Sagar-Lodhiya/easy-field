<?php

namespace app\controllers;

use app\models\EmployeePunchDetailsSearch;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yii;

class ReportController extends \yii\web\Controller
{
    public function actionAttendanceReport()
    {
        $searchModel = new EmployeePunchDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        ini_set('memory_limit', '-1'); // Ensure enough memory for large exports
        ob_clean(); // Clean output buffer

        $searchModel = new EmployeePunchDetailsSearch();
        $dataProvider = $searchModel->exportSearch(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        // Set headers
        $activeWorksheet->setCellValue('A1', 'Id');
        $activeWorksheet->setCellValue('B1', 'Name');
        $activeWorksheet->setCellValue('C1', 'Traveled KM');
        $activeWorksheet->setCellValue('D1', 'Vehicle Type');
        $activeWorksheet->setCellValue('E1', 'DA');
        $activeWorksheet->setCellValue('F1', 'TA');
        $activeWorksheet->setCellValue('G1', 'Expenses Amount');
        $activeWorksheet->setCellValue('H1', 'Total Visits');
        $activeWorksheet->setCellValue('I1', 'Total Payments');
        $activeWorksheet->setCellValue('J1', 'Created At');
        $activeWorksheet->setCellValue('K1', 'Total Distance');
        $activeWorksheet->setCellValue('L1', 'Last Log Id');
        $activeWorksheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E90FF');

        // Populate data
        $row = 2;
        foreach ($models as $model) {
            $activeWorksheet->setCellValue('A' . $row, $model->id);
            $activeWorksheet->setCellValue('B' . $row, $model->user->name);
            $activeWorksheet->setCellValue('C' . $row, $model->traveled_km);
            $activeWorksheet->setCellValue('D' . $row, $model->vehicle_type);
            $activeWorksheet->setCellValue('E' . $row, $model->da);
            $activeWorksheet->setCellValue('F' . $row, $model->ta);
            $activeWorksheet->setCellValue('G' . $row, $model->getExpenses()->sum('requested_amount') ?: 'N/A');
            $activeWorksheet->setCellValue('H' . $row, $model->getVisits()->count() ?: 'N/A');
            $activeWorksheet->setCellValue('I' . $row, $model->getPayments()->count() ?: 'N/A');
            $activeWorksheet->setCellValue('J' . $row, $model->created_at);
            $activeWorksheet->setCellValue('K' . $row, $model->total_distance);
            $activeWorksheet->setCellValue('L' . $row, $model->last_log_id);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'J') as $columnID) {
            $activeWorksheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set headers for download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Attendance-Report-' . date('YmdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Save the file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
}
