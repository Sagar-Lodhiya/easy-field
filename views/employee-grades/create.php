<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\EmployeeGrades $model */

$this->title = 'Create Employee Grades';
$this->params['breadcrumbs'][] = ['label' => 'Employee Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-grades-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
