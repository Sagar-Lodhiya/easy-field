<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Office $model */

$this->title = 'Update Office: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Offices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="office-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
