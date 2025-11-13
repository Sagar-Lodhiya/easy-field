<?php

use app\assets\DateRangeAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AdminsSearch */
/* @var $form yii\widgets\ActiveForm */

DateRangeAsset::register($this);
?>

<div class="admins-search">

    <?php $form = ActiveForm::begin([
        'action' => ['report'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'user_id')->dropDownList(
                \app\models\Users::getUserList(),
                ['class' => 'form-control select-chosen', 'prompt' => 'Select User']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'date_time_range')->textInput(['class' => 'form-control date_range']) ?>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>