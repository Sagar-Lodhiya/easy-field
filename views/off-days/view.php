<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\OffDays $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Off Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="off-days-view">

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
            'title',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model) {
                    $value = [1 => 'Weekly', 2 => 'Monthly', 3 => 'Yearly', 4 => 'Once Off'];
                    return $value[$model->type];
                },
            ],
            'date',
            'created_at',
        ],
    ]) ?>

</div>
