<?php

use app\models\LeaveType;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Leave $model */
/** @var yii\widgets\ActiveForm $form */
?>


<div class="leave-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">

            <?= $form->field($model, 'leave_type_id')->dropDownList(
                LeaveType::getLeaveTypeList(),
                ['class' => 'form-control select-chosen', 'prompt' => 'Select Leave Type']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
                'dateFormat' => 'yyyy-MM-dd', // Format for storing in the database
                'options' => ['class' => 'form-control'], // Adds Bootstrap styling
                'clientOptions' => [
                    'changeMonth' => true, // Allow changing months
                    'changeYear' => true,  // Allow changing years
                  
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
                'dateFormat' => 'yyyy-MM-dd', // Format for storing in the database
                'options' => ['class' => 'form-control'], // Adds Bootstrap styling
                'clientOptions' => [
                    'changeMonth' => true, // Allow changing months
                    'changeYear' => true,  // Allow changing years
                 
                ],
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>