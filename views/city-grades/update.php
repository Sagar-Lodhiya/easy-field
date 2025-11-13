<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CityGrades $model */

$this->title = 'Update City Grades: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'City Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="city-grades-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
