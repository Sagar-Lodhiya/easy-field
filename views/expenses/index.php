<?php

use app\models\CityGrades;
use app\models\ExpenseCategories;
use app\models\Expenses;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExpensesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_search', ['model' => $searchModel]); ?>


    <?= Html::a('Export', Url::to(['expenses/export', 'type' => Yii::$app->controller->action->id, Yii::$app->request->queryParams]), ['class' => 'btn btn-warning']) ?>




<div class="expenses-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'city_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return ($model->city_id && $model->city) ? $model->city->city : '';
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user->name . ' ' . $model->user->last_name;
                }
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->category->name;
                }
            ],
            'requested_amount',
            'approved_amount',
            'expense_date',
            [
                'class' => ActionColumn::className(),
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#1bbae1;'],
                'template' => '{view}',
                'visibleButtons' => [
                    'view' => function ($model) {
                        return $model->expense_type == 'claimed';
                    }
                ],
                'urlCreator' => function ($action, Expenses $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },

            ],
        ],
    ]); ?>


</div>