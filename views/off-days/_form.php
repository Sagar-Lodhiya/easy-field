<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\OffDays $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="off-days-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList([1 => 'Weekly', 2 => 'Monthly', 3 => 'Yearly', 4 => 'Once Off'], ['class' => 'form-control select-chosen', 'prompt' => 'Select Type']) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
