<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Users $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">

    <p style="float: right;">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'email:email',
            'parent.name',
            [
                'attribute' => 'user_type',
                'value' => function ($model) {
                    $types = \app\models\Users::getUserTypeOptions();
                    return isset($types[$model->user_type]) ? $types[$model->user_type] : $model->user_type;
                }
            ],
            'device_id',
            'device_type',
            'device_model',
            'app_version',
            'os_version',
            'device_token',
            'created_at',

        ],
    ]) ?>

</div>
