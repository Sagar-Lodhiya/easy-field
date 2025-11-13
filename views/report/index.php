<?php

use app\helpers\AppHelper;
use app\models\Admins;
use app\models\EmployeePunchDetailsSearch;
use app\models\Users;
use app\models\Expenses;
use app\models\EmployeePunchDetails;
use app\models\Visits;
use sadi01\dateRangePicker\dateRangePicker;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AttendanceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Attendance Report';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <div class="active-users-index">

        <div class="users-index">


            <div class="filter-form">
                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'action' => ['report/attendance-report'], // Correct route
                    'options' => ['class' => 'row g-3'], // Using g-3 for consistent gap between columns
                ]); ?>
                <div class="col-md-4 mb-3"> <!-- Added margin-bottom for spacing -->
                    <?= $form->field($searchModel, 'user_id')->dropDownList(
                        \yii\helpers\ArrayHelper::map(
                            Users::find()->all(), // Replace with your actual User model query
                            'id', // The 'id' field for the value
                            'name' // The 'username' field for the label
                        ),
                        [
                            'prompt' => 'Select User', // Placeholder for dropdown
                            'class' => 'form-control',
                            'options' => [
                                $searchModel->user_id => ['Selected' => true], // Set selected value from model data
                            ]
                        ]
                    )->label('User Name') ?>
                </div>

                <div class="col-md-4 mb-3">
                    <?= $form->field($searchModel, 'vehicle_type')->dropDownList(
                        \yii\helpers\ArrayHelper::map(
                            EmployeePunchDetails::find()->select('vehicle_type')->distinct()->all(), // Fetch unique vehicle types
                            'vehicle_type', // The 'vehicle_type' field for the value
                            'vehicle_type' // The 'vehicle_type' field for the label
                        ),
                        [
                            'prompt' => 'Select Vehicle Type', // Placeholder for dropdown
                            'class' => 'form-control',
                            'options' => [
                                $searchModel->vehicle_type => ['Selected' => true], // Set selected value from model data
                            ]
                        ]
                    )->label('Vehicle Type') ?>
                </div>

                <div class="col-md-4 mb-3"> <!-- Added margin-bottom for spacing -->
                    <?= $form->field($searchModel, 'da')->textInput([
                        'placeholder' => 'Search by DA',
                        'class' => 'form-control',
                    ])->label('DA') ?>
                </div>

                <div class="col-md-4 mb-3"> <!-- Added margin-bottom for spacing -->
                    <?= $form->field($searchModel, 'ta')->textInput([
                        'placeholder' => 'Search by TA',
                        'class' => 'form-control',
                    ])->label('TA') ?>
                </div>

                <div class="col-md-4 mb-3">
                    <?= $form->field($searchModel, 'date_time_range')->widget(dateRangePicker::classname(), [
                        'options' => [
                            'drops' =>  'down',
                            'placement' => 'left',
                            'opens' => 'left',
                            'language' => 'en', // Set to 'en' for English
                            'showDropdowns' => true,
                            'singleDatePicker' => false,
                            'locale' => [
                                'format' => 'YYYY/MM/DD HH:mm:ss', // Change to a typical English date format
                            ],
                        ],
                        'htmlOptions' => [
                            'class' => 'form-control',
                            'id' => 'date_time_range',
                            'autocomplete' => 'off',
                        ]
                    ]); ?>


                </div>

                <div class="col-md-12 mt-3"> <!-- Added margin-top for spacing -->
                    <div class="d-flex justify-content-start">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary me-2']) ?>
                        <?= Html::a('Reset', ['report/attendance-report'], ['class' => 'btn btn-secondary']) ?>

                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <div style="float:right">
                <?= Html::a('Export', Url::to(array_merge(['export'], Yii::$app->request->queryParams)), ['class' => 'btn btn-warning']) ?>
            </div>
            <div class="active-users-index">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'name',
                            'headerOptions' => ['style' => 'color:#1bbae1;'],
                            'value' => function ($model) {
                                return $model->user->name;
                            },
                        ],
                        'traveled_km',
                        'vehicle_type',
                        [
                            'label' => 'Punch In',
                            'headerOptions' => ['style' => 'color:#1bbae1'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                $imagePath = Yii::getAlias('@web') . '/uploads/' . basename($model->punch_in_image);
                                return file_exists(Yii::getAlias('@webroot') . '/uploads/' . basename($model->punch_in_image))
                                    ? Html::img($imagePath, ['width' => '100px']) . '<br/>' . $model->punch_in_place . '<br/>' . $model->punch_in_meter_reading_in_km
                                    : 'No Image';
                            },
                        ],
                        [
                            'label' => 'Punch Out',
                            'headerOptions' => ['style' => 'color:#1bbae1'],
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->punch_out_image == null) {
                                    return 'No Image';
                                } else {
                                    $imagePath = Yii::getAlias('@web') . '/uploads/' . basename($model->punch_out_image);
                                }
                                return file_exists(Yii::getAlias('@webroot') . '/uploads/' . basename($model->punch_out_image))
                                    ? Html::img($imagePath, ['width' => '100px']) . '<br/>' . $model->punch_out_place . '<br/>' . $model->punch_out_meter_reading_in_km
                                    : 'No Image';
                            },
                        ],

                        'da',
                        'ta',

                        [
                            'label' => 'Expenses Amount',
                            'value' => function ($model) {
                                return $model->getExpenses()->sum('requested_amount') ?: 'N/A';
                            },
                        ],
                        [
                            'label' => 'Total Visits',
                            'value' => function ($model) {
                                return $model->getVisits()->count() ?: 'N/A';
                            },
                        ],
                        [
                            'label' => 'Total Payments',
                            'value' => function ($model) {
                                return $model->getPayments()->count() ?: 'N/A';
                            },
                        ],
                        'created_at',
                        'total_distance',
                        'last_log_id',
                    ],
                ]); ?>

            </div>
        </div>
    </div>