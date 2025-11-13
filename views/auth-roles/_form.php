<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthRoles $model */
/** @var app\models\AuthModules $authModules */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="auth-roles-form">

    <?php $form = ActiveForm::begin(); ?>
    <table class="table table-bordered"> 
    <thead>
            <tr>
            <th><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></th>
            </tr>
            </thead>
       
    <tbody>
        <?php foreach ($authModules as $module) { ?>
            <tr>
                <td>
                    <?= $module->name ?>
                </td>
                    <td>
                            <?= $form->field($model, 'authPermissions[' . $module->id . ']')->checkboxList( \yii\helpers\ArrayHelper::map($module->authItems, 'id', 'name'), [

                                'item' => function ($index, $label, $name, $checked, $value) {
                                    $checkedAttribute = $checked ? 'checked' : '';
                                    return "<div 'class'='form-group field-authroles d-flex'>
                                            <label class='control-label'>
                                                <input tabindex='{$index}' class='from-control' type='checkbox' {$checkedAttribute} name='{$name}'value='{$value}'> 
                                                    {$label}
                                                </label>
                                            </div>";
                                }
                            ])->label(false); ?>
                </td>
                </tr>
        <?php } ?>
        </tbody>
        </table>
    


    <div class="form-group" style="float: right;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>