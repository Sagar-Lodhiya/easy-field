<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */

$this->title = 'Update Expense Categories: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expense-categories-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
