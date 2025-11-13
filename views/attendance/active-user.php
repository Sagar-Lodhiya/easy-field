<?php

use app\helpers\AppHelper;
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

$this->title = 'Active Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <div class="active-users-index">

        <div class="users-index">
            <div class="active-users-index">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'name',
                            'headerOptions' => ['style' => 'color:#1bbae1;'],
                            'value' => function ($model) {
                                return $model->user->name;
                            },
                        ],
                        [
                            'label' => 'Last Log Date',
                            'headerOptions' => ['style' => 'color:#1bbae1;'],
                            'value' => function ($model) {
                                return $model->lastLog->created_at ?? '';
                            },
                        ],
                        [
                            'attribute' => 'email',
                            'headerOptions' => ['style' => 'color:#1bbae1;'],
                            'value' => function ($model) {
                                return $model->user->email;
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => 'Punch In Date',
                            'headerOptions' => ['style' => 'color:#1bbae1;'],
                        ],
                        'traveled_km',
                        'vehicle_type',
                        [
                            'attribute' => 'punch_in_image',
                            'label' => 'Punch-In Image',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (!empty($model->punch_in_image)) {
                                    $fileName = basename($model->punch_in_image);
                                    $imagePath = Yii::getAlias('@web') . '/uploads/' . $fileName;
                                    $filePath = Yii::getAlias('@webroot') . '/uploads/' . $fileName;

                                    if (file_exists($filePath)) {
                                        return Html::img($imagePath, ['width' => '100px']);
                                    }
                                }
                                return 'No Image';
                            },
                        ],

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
                        ],
                    ],
                ]); ?>

            </div>
        </div>




    </div>