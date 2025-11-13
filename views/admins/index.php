<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use yii\helpers\ArrayHelper;
use app\models\Admins;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admins';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('admins');
$permission = $permissionData['permissions'];

?>
<div class="admins-index">

    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Admin', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'email:email',
            [
                'attribute' => 'parent_id',
                'format' => 'raw',
                'value' => function ($model) {
                    // Show "Super Admin" if parent_id is null, else show the parent admin's name
                    if ($model->parent_id === null) {
                        return Html::tag('span', 'Super Admin', ['class' => 'label label-primary']);
                    } else {
                        return Html::encode($model->parent->name);
                    }
                },
                'label' => 'Parent Admin',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'parent_id',
                    ArrayHelper::merge(
                        ['' => 'Filter by Parent Admin', '0' => 'Super Admin'], // ✅ Fixed: Using empty string as key
                        ArrayHelper::map(Admins::find()->where(['is_deleted' => 0])->all(), 'id', 'name')
                    ),
                    ['class' => 'form-control select-chosen']
                ),
            ],

            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model) {
                    // Show "Super Admin" if parent_id is null, else show the parent admin's name
                    if ($model->type === 1) {
                        return '<span class="label label-primary"> Super Admin</span>';
                    } else if ($model->type === 2) {
                        return '<span class="label label-warning"> Admin</span>';
                    } else {
                        return '<span class="label label-success"> Sub Admin</span>';
                    }
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', [1 => 'Super Admin', 2 => 'Admin', 3 => 'Sub Admin'], ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Type']),
            ],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->id !== Yii::$app->user->identity->id) {
                        return '<label class="switch switch-primary">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "",
                                'id' => "myonoffswitch" . $model->id,
                                'onclick' => 'app.changeStatus("admins/activate",this,' . $model->id . ')'
                            ]) .
                            '<span></span>' .
                            '</label>';
                    }
                    return 'Active';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => function ($model) {
                    return date('Y-m-d', strtotime($model->created_at));
                },
                'filter' => false
            ],
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