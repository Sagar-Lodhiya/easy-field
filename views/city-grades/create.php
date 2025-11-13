<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CityGrades $model */

$this->title = 'Create City Grades';
$this->params['breadcrumbs'][] = ['label' => 'City Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-grades-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
