<?php

use app\models\Payment;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\AppHelper;
use app\models\PaymentsSearch;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'label' => 'User Name',
                'value' => function ($model) {
                    return $model->user ? $model->user->name : 'N/A'; // Assuming the relation exists
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_id',
                    ArrayHelper::map(
                        \app\models\Users::find()->select(['id', 'name'])->all(), // Fetch from the related table
                        'id',
                        'name'
                    ),
                    ['class' => 'form-control', 'prompt' => 'Select User']

                )

            ],

            [
                'attribute' => 'party_id',
                'label' => 'Party Name',
                'value' => function ($model) {
                    return $model->parties ? $model->parties->dealer_name : 'N/A'; // Assuming the relation exists
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'party_id',
                    ArrayHelper::map(
                        \app\models\Parties::find()->select(['id', 'dealer_name'])->all(), // Fetch from the related table
                        'id',
                        'dealer_name'
                    ),
                    ['class' => 'form-control', 'prompt' => 'Select Party']
                ),
            ],

            'amount',
            'amount_type',
            'amount_details',
            'collection_of',
            'payments_details',
            'extra',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status == 1) {
                        return '<a href="javascript:void(0)" class="label label-warning">Pending</a>';
                    } elseif ($model->status == 2) {
                        return '<a href="javascript:void(0)" class="label label-success">Approved</a>';
                    } else {
                        return '<a href="javascript:void(0)" class="label label-danger">Declined</a>';
                    }
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', [2 => 'Approved', 3 => 'Declined', 1 => 'Pending'], ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),

            ],
            [
                'attribute' => 'created_at',
                'label' => 'Date',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDate($model->created_at, 'php:Y-m-d');
                },
            ],
            [
                'class' => ActionColumn::class,
                'header' => 'Actions',
                'template' => '{approve} {decline}',
                'headerOptions' => ['style' => 'color:#1bbae1; "text-align: center;"'],
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 102.97" style="display:inline-block; font-size:inherit; height:1em; width:auto; vertical-align:middle;"><defs><style>.checkmark { fill: #10a64a; }</style></defs><title>small-check-mark</title><path class="checkmark" d="M4.82,69.68c-14.89-16,8-39.87,24.52-24.76,5.83,5.32,12.22,11,18.11,16.27L92.81,5.46c15.79-16.33,40.72,7.65,25.13,24.07l-57,68A17.49,17.49,0,0,1,48.26,103a16.94,16.94,0,0,1-11.58-4.39c-9.74-9.1-21.74-20.32-31.86-28.9Z"/></svg>',
                            $url,
                            [
                                'class' => '',
                                'style' => 'margin-right:5px;', 
                                'title' => 'Approve',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                    'decline' => function ($url, $model, $key) {
                        return Html::a(
                            '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 122.88" style="display:inline-block; font-size:inherit; height:1.25em; width:auto; vertical-align:middle;"><defs><style>.crossmark { fill: #f44336; fill-rule: evenodd; }</style></defs><title>close-red</title><path class="crossmark" d="M61.44,0A61.44,61.44,0,1,1,0,61.44,61.44,61.44,0,0,1,61.44,0ZM74.58,36.8c1.74-1.77,2.83-3.18,5-1l7,7.13c2.29,2.26,2.17,3.58,0,5.69L73.33,61.83,86.08,74.58c1.77,1.74,3.18,2.83,1,5l-7.13,7c-2.26,2.29-3.58,2.17-5.68,0L61.44,73.72,48.63,86.53c-2.1,2.15-3.42,2.27-5.68,0l-7.13-7c-2.2-2.15-.79-3.24,1-5l12.73-12.7L36.35,48.64c-2.15-2.11-2.27-3.43,0-5.69l7-7.13c2.15-2.2,3.24-.79,5,1L61.44,49.94,74.58,36.8Z"/></svg>',
                            $url,
                            [
                                'class' => '', 
                                'title' => 'Decline',
                                'data-pjax' => '0',
                            ]
                        );
                    },

                ],
            ],

        ],
    ]); ?>



</div>