<?php

use app\models\PartyCategories;
use app\models\Users;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Parties $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="parties-form">

    <?php $form = ActiveForm::begin(); ?>



    <fieldset class="fieldset">
        <legend>Employee Information</legend>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'employee_id')->dropDownList(
                    Users::getUserList(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select Employee']
                )->label('User Name <span style="color:red;">*</span>', ['encode' => false])  ?>
            </div>
        </div>

    </fieldset>
    <fieldset class="fieldset">
        <legend>Firm Information</legend>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'firm_name')->textInput(['maxlength' => true])->label('Firm Name <span style="color:red;">*</span>', ['encode' => false]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'dealer_name')->textInput(['maxlength' => true])->label('Dealer Name <span style="color:red;">*</span>', ['encode' => false]) ?>

            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <?= $form->field($model, 'party_category_id')->dropDownList(
                    PartyCategories::getPartyCategoriesList(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select Party Category']
                )->label('Party Category <span style="color:red;">*</span>', ['encode' => false])  ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'dealer_phone')->textInput(['maxlength' => true])->label('Dealer Phone <span style="color:red;">*</span>', ['encode' => false]) ?>

            </div>
        </div>

    </fieldset>

    <fieldset class="fieldset">
        <legend>Location Information</legend>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'city_or_town')->textInput(['maxlength' => true])->label('City Or Town <span style="color:red;">*</span>', ['encode' => false]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

    </fieldset>
    <fieldset class="fieldset">
        <legend>Other Information</legend>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'gst_number')->textInput(['maxlength' => true])->label('GST Number <span style="color:red;">*</span>', ['encode' => false]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'dealer_aadhar')->textInput(['maxlength' => true])->label('Dealer Aadhar <span style="color:red;">*</span>', ['encode' => false]) ?>
            </div>
        </div>

    </fieldset>

    <div class="form-group" style="float: right; margin-top: 20px">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>