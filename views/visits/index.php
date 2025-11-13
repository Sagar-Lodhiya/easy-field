<?php

use app\models\Visits;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\Users;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\api\models\VisitsForm $searchModel */

$this->title = 'Visits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visits-index">


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
                    return $model->party ? $model->party->dealer_name : 'N/A'; // Assuming the relation exists
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
            'place',
            'duration',
            [
                'attribute' => 'discussion_point',
                'filter' => Html::textInput('VisitsSearch[discussion_point]', $searchModel->discussion_point, ['class' => 'form-control']),
            ],
            'remark',
            'created_at',

        ],
    ]); ?>

</div>