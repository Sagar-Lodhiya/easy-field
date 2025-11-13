<?php

use app\models\Admins;
use app\models\EmployeeGrades;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <fieldset class="fieldset">
        <legend>Contact Information</legend>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'parent_id')->dropDownList(
                    Admins::getAdminsList(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select Parent']
                )  ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'employee_grade_id')->dropDownList(
                    EmployeeGrades::getEmployeeGradeList(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select Employee Grade']
                )  ?>
            </div>
        </div>

    </fieldset>

    <fieldset class="fieldset">
        <legend>Other Information</legend>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'user_type')->dropDownList(
                    \app\models\Users::getUserTypeOptions(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select User Type']
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'profile')->fileInput() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'da_amount')->textInput(['type' => 'number', 'maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'da_with_night_stay_amount')->textInput(['type' => 'number', 'maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'eligible_km')->textInput(['type' => 'number', 'maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Office Punch-in Enabled</label><br>
                <label class="switch">
                    <?= Html::activeCheckbox(
                        $model,
                        'office_punchin_enabled',
                        [
                            'label' => false,
                            'uncheck' => 0,
                            'value' => 1,
                            'checked' => $model->office_punchin_enabled == 1,
                        ]
                    ) ?>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        <style>
        .switch {
          position: relative;
          display: inline-block;
          width: 50px;
          height: 24px;
        }
        .switch input {display:none;}
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0; left: 0; right: 0; bottom: 0;
          background-color: #ccc;
          transition: .4s;
          border-radius: 24px;
        }
        .slider:before {
          position: absolute;
          content: "";
          height: 18px;
          width: 18px;
          left: 3px;
          bottom: 3px;
          background-color: white;
          transition: .4s;
          border-radius: 50%;
        }
        .switch input:checked + .slider {
          background-color: #2196F3;
        }
        .switch input:checked + .slider:before {
          transform: translateX(26px);
        }
        </style>

    </fieldset>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>