<?php

use app\helpers\PermissionHelper;
use app\models\Leave;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LeavesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Leaves';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('leave');
$permission = $permissionData['permissions'];

?>
<div class="leave-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'leave_type',
                'headerOptions' => ['style' => 'color:#1bbae1;'],
                'label' => 'Leave Type',
                'value' => function ($model) {
                    return $model->leaveType->leave_type ?? 'N/A'; // Handle the related leave type
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'leave_type_id',
                    \yii\helpers\ArrayHelper::map(\app\models\LeaveType::find()->all(), 'id', 'leave_type'),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Leave Type']
                ),
            ],


            [
                'attribute' => 'user_id',
                'headerOptions' => ['style' => 'color:#1bbae1;'],
                'label' => 'Name',
                'value' => function ($model) {
                    return $model->user->name;
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'user_id',
                    \yii\helpers\ArrayHelper::map(\app\models\Users::find()->all(), 'id', 'name'),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Filter By User Name']
                ),

            ],
            'start_date',
            'end_date',
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
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    [2 => 'Approved', 3 => 'Declined', 1 => 'Pending'],
                    ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']
                ),
            ],


            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Leave $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{update}',
                'visibleButtons' => [
                    'update' => function($model) use($permission) {
                        return $permission['update'] && $model->status == 1;
                    }
                ],
            ],
        ],
    ]); ?>


</div>