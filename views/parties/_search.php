<?php

use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Users;
use app\models\PartyCategories;

/** @var yii\web\View $this */
/** @var app\models\PartiesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="parties-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'employee_id')->dropDownList(
                ArrayHelper::merge(['' => 'Select User'], Users::getUserList()),
                ['class' => 'form-control select-chosen']
            ) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'party_category_id')->dropDownList(
                ArrayHelper::merge(['' => 'Select Party Category'], PartyCategories::getPartyCategoriesList()),
                ['class' => 'form-control select-chosen']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'dealer_name') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dealer_phone') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'firm_name') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'city_or_town') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'is_active')->dropDownList(
                [1 => 'Active', 0 => 'Inactive'],
                ['class' => 'form-control', 'prompt' => 'Select Status']
            ) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', BaseUrl::toRoute('./parties'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
