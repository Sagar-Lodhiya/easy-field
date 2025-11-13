<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expenses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'requested_amount')->textInput() ?>

    <?= $form->field($model, 'approved_amount')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_night_stay')->textInput() ?>

    <?= $form->field($model, 'expense_photo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expense_details')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin_id')->textInput() ?>

    <?= $form->field($model, 'expense_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
