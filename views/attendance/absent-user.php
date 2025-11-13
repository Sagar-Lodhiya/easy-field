<?php

use app\helpers\AppHelper;
use app\models\Admins;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UsersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Absent Users';
$this->params['breadcrumbs'][] = $this->title;
?>
 


 <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'label' => 'Name',
        ],
        'phone_no',
        'email',
      
    ],
]); ?>



</div>
