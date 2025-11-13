<?php

use app\helpers\PermissionHelper;
use app\models\Cms;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CmsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cms';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('cms');
$permission = $permissionData['permissions'];

?>
<div class="cms-index">
 
    
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'page',
            'title',
            'description:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Cms $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                 'visibleButtons' => [
                    'update' => $permission['update'],
                 ]
            ],
        ],
    ]); ?>


</div>
