<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\ExpenseCategories;
use yii\bootstrap5\Dropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategoriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Expense Categories';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('expense-categories');
$permission = $permissionData['permissions'];

?>
<div class="expense-categories-index">

    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Expense Categories', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'filter' => Html::activeDropDownList($searchModel, 'id', ExpenseCategories::getExpenseCategoriesList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Category']),
            ],
            [
                'attribute' => 'type',
                'filter' => Html::activeDropDownList($searchModel, 'type', ExpenseCategories::getTypeList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Type']),
            ],
             [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("expense-categories/activate",this,' . $model->id . ')'
                        ]) .
                        '<span></span>' .
                        '</label>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
            ],
            'created_at',
            [
                'class' => ActionColumn::class,
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#1bbae1;'],
                'visibleButtons' => [
                    'update' => $permission['update'],
                    'delete' => $permission['delete'],
                    'view' => $permission['view'],
                ],
            ],
        ],
    ]); ?>


</div>