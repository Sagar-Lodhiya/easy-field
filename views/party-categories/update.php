<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartyCategories $model */

$this->title = 'Update Party Categories: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Party Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="party-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
