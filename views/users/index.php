<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\Admins;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UsersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('users');
$permission = $permissionData['permissions'];

?>
<div class="users-index">


    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Users', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

   

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'phone_no',
            'email:email',
            [
                'attribute' => 'parent_id',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'parent_id', Admins::getAdminsList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Parent']),
                'value' => function ($model) {
                    return $model->parent->name;
                }
            ],
            [
                'attribute' => 'user_type',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->user_type === 'sales') {
                        return '<span class="label label-primary">' . $model->getUserTypeLabel() . '</span>';
                    } else if ($model->user_type === 'office') {
                        return '<span class="label label-warning">' . $model->getUserTypeLabel() . '</span>';
                    } else if ($model->user_type === 'sales_and_office') {
                        return '<span class="label label-success">' . $model->getUserTypeLabel() . '</span>';
                    } else {
                        return '<span class="label label-info">' . $model->getUserTypeLabel() . '</span>';
                    }
                },
                'filter' => Html::activeDropDownList($searchModel, 'user_type', Users::getUserTypeOptions(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By User Type']),
            ],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("users/activate",this,' . $model->id . ')'
                        ]) .
                        '<span></span>' .
                        '</label>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
            ],
            'device_model', 
            'created_at',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update} {delete} {reset}',
                'buttons' => [
                    'reset' => function ($url, $model, $key) {
                        return Html::beginForm(['users/reset-device'], 'post', [
                            'style' => 'display:inline;',
                            'onsubmit' => 'return confirm("Are you sure you want to reset this user?");' // Confirmation message
                        ])
                            . Html::hiddenInput('id', $model->id)
                            . Html::submitButton(
                                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle;">
                <path fill="#1bbae1" d="M12 2a10 10 0 0 1 10 10h2l-3 3-3-3h2a8 8 0 1 0-2.34 5.66l1.42 1.42A10 10 0 1 1 12 2ZM11 6h2v7h-2V6Zm1 10a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>
            </svg>',
                                ['title' => 'Reset', 'style' => 'border: none; background: none; padding: 0; cursor: pointer;']
                            )
                            . Html::endForm();
                    },


                ],
                'visibleButtons' => [
                    'update' => $permission['update'],
                    'delete' => $permission['delete'],
                    'view' => $permission['view'],
                    'reset' => PermissionHelper::checkUserHasAccess('users', 'reset-device'),
                ],
            ],
        ],
    ]); ?>


</div>