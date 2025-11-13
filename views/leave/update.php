<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Leave $model */

$this->title = 'Update Leave: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Leaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="leave-update">

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->leaveType->leave_type;
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user->name .' '.$model->user->last_name;
                }
            ],
            'start_date',
            'end_date',
            'reason:ntext',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    switch ($model->status) {
                        case 1:
                            return '<span class="label label-warning">Pending</span>';
                        case 2:
                            return '<span class="label label-success">Approved</span>';
                        case 3:
                            return '<span class="label label-danger">Declined</span>';
                        default:
                            return '<span class="label label-default">Unknown</span>';
                    }
                }
            ],
        ],
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
