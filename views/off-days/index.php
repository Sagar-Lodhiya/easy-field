<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\OffDays;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\OffDaysSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Off Days';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('off-days');
$permission = $permissionData['permissions'];

?>
<div class="off-days-index">

    <p style="float: right;">
        <?= ($permission['create']) ?  Html::a('Create Off Days', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model) {
                    $value = [1 => 'Weekly', 2 => 'Monthly', 3 => 'Yearly', 4 => 'Once Off'];
                    return $value[$model->type];
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', OffDays::getTypeOptions(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Type']),
            ],
            'date',
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("off-days/activate",this,' . $model->id . ')'
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