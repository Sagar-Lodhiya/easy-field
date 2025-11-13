<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/** @var yii\web\View $this */
/** @var app\models\Cms $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cms-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'description')->widget(TinyMce::class, [
                'options' => ['rows' => 8],
                'language' => 'en',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link image charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table paste code help wordcount"
                    ],
                    'toolbar' => "undo redo | formatselect | bold italic | 
                                  alignleft aligncenter alignright alignjustify | 
                                  bullist numlist outdent indent | link image | code",
                    'menubar' => false,
                    'height' => 400,
                    'branding' => false,
                ],
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
