<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\Admins;
use app\models\Users;
use app\models\EmployeePunchDetails;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AttendanceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Attendance Report';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('attendance');
$permission = $permissionData['permissions'];

?>
<div class="users-index">

    <div class="active-users-index">

        <div class="users-index">
            <div class="active-users-index">
            <?php echo $this->render('_report_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => false,
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
                        'total_distance',
                        'vehicle_type',
                        [
                            'label' => 'Punch In',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $webPath = Yii::getAlias('@web') . '/uploads/' . basename($model->punch_in_image);
                                $filePath = Yii::getAlias('@webroot') . '/uploads/' . basename($model->punch_in_image);

                                return file_exists($filePath)
                                    ? Html::img($webPath, ['width' => '100px'])
                                    : 'No Image';
                            },
                        ],
                        [
                            'label' => 'Punch Out',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->punch_out_image == null) {
                                    return 'No Image';
                                }

                                $webPath = Yii::getAlias('@web') . '/uploads/' . basename($model->punch_out_image);
                                $filePath = Yii::getAlias('@webroot') . '/uploads/' . basename($model->punch_out_image);

                                return file_exists($filePath)
                                    ? Html::img($webPath, ['width' => '100px'])
                                    : 'No Image';
                            },
                        ],

                        'da',
                        'ta',
                        'created_at',
                        [
                            'class' => ActionColumn::class,
                            'header' => 'Actions',
                            'template' => '{detail}',
                            'headerOptions' => ['style' => 'color:#1bbae1; "text-align: center;"'],
                            'buttons' => [
                                'detail' => function ($url, $model, $key) {
                                    return Html::a(
                                        '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                                        $url,
                                        [
                                            'class' => '',
                                            'title' => 'Detail',
                                            'data-pjax' => '0',
                                        ]
                                    );
                                },
                            ],
                            'visibleButtons' => [
                                'detail' => isset($permission['detail']) ? $permission['detail'] : (isset($permission['view']) ? $permission['view'] : true),
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>




    </div>