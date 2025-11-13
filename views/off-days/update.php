<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OffDays $model */

$this->title = 'Update Off Days: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Off Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="off-days-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
