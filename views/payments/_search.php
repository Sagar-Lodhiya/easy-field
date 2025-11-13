<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\api\models\PaymentsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payments-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'party_id') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'amount_type') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'collection_of') ?>

    <?php // echo $form->field($model, 'payments_details') ?>

    <?php // echo $form->field($model, 'payment_of') ?>

    <?php // echo $form->field($model, 'payments_photo') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
