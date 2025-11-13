<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\CityGrades;
use app\models\Users;
use app\models\ExpenseCategories;

/** @var yii\web\View $this */
/** @var app\models\ExpensesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="expenses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'city_id')->dropDownList(
                ArrayHelper::map(CityGrades::find()->select(['id', 'city'])->all(), 'id', 'city'),
                ['class' => 'form-control', 'prompt' => 'Select City']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'user_id')->dropDownList(
                ArrayHelper::map(Users::find()->select(['id', 'name'])->all(), 'id', 'name'),
                ['class' => 'form-control', 'prompt' => 'Select User']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(ExpenseCategories::find()->select(['id', 'name'])->all(), 'id', 'name'),
                ['class' => 'form-control', 'prompt' => 'Select Category']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'requested_amount')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Requested Amount']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'approved_amount')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Approved Amount']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'expense_date')->textInput(['class' => 'form-control']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>