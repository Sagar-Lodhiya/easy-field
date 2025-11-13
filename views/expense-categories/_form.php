<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expense-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList(['limited_by_price' => 'Limited by price', 'no_price_limit' => 'No price limit',], ['class' => 'form-control select-chosen', 'prompt' => 'Select Expense Category']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>