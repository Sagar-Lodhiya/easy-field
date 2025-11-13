<?php

use app\helpers\PermissionHelper;
use app\models\Admins;
use app\models\Notifications;
use app\models\Users;
use Codeception\Lib\Notification;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\NotificationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('notifications');
$permission = $permissionData['permissions'];

?>
<div class="notifications-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Notifications', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'user_id', Users::getUserList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By User']),
                'value' => function ($model) {
                    return $model->user->name . ' ' . $model->user->last_name;
                }
            ],
            'text',

            [
                'attribute' => 'admin_id',
                'label' => 'Admin',
                'filter' => Html::activeDropDownList($searchModel, 'admin_id', Admins::getAdminsList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Admin']),

                'value' => function ($model) {
                    return $model->admin->name ?? '(no admin)'; //
                }
            ],
            'is_read',
            'is_cleared',
            'created_at',
            //'updated_at',
        ],


    ]); ?>


</div>