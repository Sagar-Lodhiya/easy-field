<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\LeaveType $model */

$this->title = 'Update Leave Type: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Leave Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="leave-type-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
