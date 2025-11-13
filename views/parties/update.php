<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Parties $model */

$this->title = 'Update Parties: ' . $model->firm_name;
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->firm_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parties-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
