<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\States $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="states-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">   
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group" style="float: right;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
