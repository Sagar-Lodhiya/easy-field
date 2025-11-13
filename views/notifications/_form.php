<?php
use app\models\Users;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Notifications $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="notifications-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'user_id')->dropDownList(
                    Users::getUserList(),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Select users']
                )  ?>
    
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>