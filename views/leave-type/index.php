<?php

use app\helpers\PermissionHelper;
use app\models\LeaveType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LeaveTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Leave Types';  
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('leave-type');
$permission = $permissionData['permissions'];

?>
<div class="leave-type-index">



    <p style="float:right">
        <?= ($permission['create']) ? Html::a('Create Leave Type', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'leave_type',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LeaveType $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'visibleButtons' => [
                    'view' => $permission['view'],
                    'update' => $permission['update'],
                    'delete' => $permission['delete'],
                ]
            ],
        ],
    ]); ?>


</div>