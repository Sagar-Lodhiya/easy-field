<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthRoles $model */
/** @var app\models\AuthModules[] $authModules */
/** @var array $assignedPermissions */

$this->title = 'Update Auth Roles: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auth Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-roles-update">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])?>
    
    <h3>Permissions</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Module</th>
                <th>Permissions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authModules as $module) { ?>
                <tr>
                    <td><strong><?= Html::encode($module->name) ?></strong></td>
                    <td>
                        <?php foreach ($module->authItems as $authItem) { ?>
                            <div class="form-check">
                                <?= Html::checkbox(
                                    "permissions[]", 
                                    in_array($authItem->id, $assignedPermissions), // Pre-check if assigned
                                    [
                                        'value' => $authItem->id, 
                                        'class' => 'form-check-input',
                                        'id' => 'perm-' . $authItem->id
                                    ]
                                ) ?>
                                <label class="form-check-label" for="perm-<?= $authItem->id ?>">
                                    <?= Html::encode($authItem->name) ?>
                                </label>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <div class="form-group" style="float: right;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-info'])?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>
