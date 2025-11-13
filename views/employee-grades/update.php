<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EmployeeGrades $model */

$this->title = 'Update Employee Grades: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employee Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-grades-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
