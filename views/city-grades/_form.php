<?php

use app\models\States;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CityGrades $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="city-grades-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'state_id')->dropDownList(States::getStatesList(), ['class' => 'form-control select-chosen', 'prompt' => 'Select State']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'grade_id')->dropDownList([1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D'], ['class' => 'form-control select-chosen', 'prompt' => 'Select Grade']) ?>
        </div>
    </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>