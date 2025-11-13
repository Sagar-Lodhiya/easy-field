<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cms $model */

$this->title = 'Update Cms: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Cms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cms-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
