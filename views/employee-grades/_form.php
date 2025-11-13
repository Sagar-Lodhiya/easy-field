<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GradeForm */

$this->title = 'Grade Form';
?>

<div class="grade-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">

        <?= $form->field($model, "name")->textInput([]) ?>

        <?php foreach ($model->grades as $index => $grade): ?>
            <fieldset class="fieldset">
                <legend>Grade: <?= Html::encode($grade['type']) ?></legend>

                <div class="grade-item">

                    <!-- Grade Type -->
                    <?= $form->field($model, "grades[$index][type]")->hiddenInput(['value' => $grade['type_id']])->label(false) ?>

                    <div class="form-group">
                        <?php foreach ($model->categories as $categoryIndex => $category): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-primary text-capitalize fs-4"><?= $category->name ?></h4>
                                    <?= $form->field($model, "grades[$index][categories][$categoryIndex][category_id]")->hiddenInput([
                                        'value' => $category->id
                                    ])->label(false) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, "grades[$index][categories][$categoryIndex][amount]")->textInput() ?>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>