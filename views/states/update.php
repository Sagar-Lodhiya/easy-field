<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\States $model */

$this->title = 'Update States: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'States', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="states-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
