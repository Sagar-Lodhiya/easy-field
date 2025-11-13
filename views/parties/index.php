<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\Parties;
use app\models\PartyCategories;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PartiesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Parties';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('parties');
$permission = $permissionData['permissions'];

?>
<div class="parties-index">

 
    <?php // ($permission['export']) ? Html::a('Export', Url::to(array_merge(['export'], Yii::$app->request->queryParams)), ['class' => 'btn btn-warning']) : '' 
    ?>
    <?php // ($permission['upload']) ? Html::a('Upload', ['upload'], ['class' => 'btn btn-success', 'style' => 'margin-right: 2 0px']) : ''
    ?>


    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Parties', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'employee_id',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'employee_id', Users::getUserList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Employee']),
                'value' => function ($model) {
                    return $model->employee->name;
                }
            ],
            [
                'attribute' => 'party_category_id',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'party_category_id', PartyCategories::getPartyCategoriesList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Party Category']),
                'value' => function ($model) {
                    return $model->partyCategory->name;
                }
            ],
            'dealer_name',
            'dealer_phone',
            'firm_name',
            'city_or_town',
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("parties/activate",this,' . $model->id . ')'
                        ]) .
                        '<span></span>' .
                        '</label>';
                },
                // 'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
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