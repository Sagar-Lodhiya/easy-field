<?php

use app\models\Admins;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Admins */
/* @var $form yii\widgets\ActiveForm */

$superAdmins = Admins::find()->where(['type' => 1])->all();
$admins = Admins::find()->where(['type' => 2])->all();

?>

<div class="admins-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => (!$model->isNewRecord) ? true : false]) ?>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'type')->dropDownList(
                \app\models\Admins::getTypeOptions(),
                ['prompt' => 'Select Admin Type', 'id' => 'admin-type']
            ) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'parent_id')->dropDownList(
                Admins::getParentList(),
                ['class' => 'form-control select-chosen', 'prompt' => 'Select Parent']
            ) ?>
        </div>


    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$superAdminsJs = json_encode(ArrayHelper::map($superAdmins, 'id', 'name'));
$adminsJs = json_encode(ArrayHelper::map($admins, 'id', 'name'));
$script = <<<JS
    let superAdmins = $superAdminsJs;
    let admins = $adminsJs;

    // Function to set the parent dropdown options based on type selection
    function updateParentDropdown(type) {
        let parentDropdown = $('#admins-parent_id');
        parentDropdown.empty(); // Clear the parent dropdown

        if (type == 1) {
            // Super Admin: Set parent_id to null and disable
            parentDropdown.append('<option value="">None</option>');
            parentDropdown.prop('disabled', true);  // Disable the parent dropdown
        } else if (type == 2) {
            // Admin: Set parent_id to a Super Admin
            parentDropdown.prop('disabled', false);
            parentDropdown.append('<option value="">Select Parent</option>');
            $.each(superAdmins, function(id, name) {
                parentDropdown.append('<option value="' + id + '">' + name + '</option>');
            });
            // Auto-select a Super Admin as parent
            if ($('#admins-parent_id').val() === '') {
                parentDropdown.val(superAdmins[0].id).trigger('change');
            }
        } else if (type == 3) {
            // Sub Admin: Set parent_id to an Admin
            parentDropdown.prop('disabled', false);
            parentDropdown.append('<option value="">Select Parent</option>');
            $.each(admins, function(id, name) {
                parentDropdown.append('<option value="' + id + '">' + name + '</option>');
            });
            // Auto-select an Admin as parent
            if ($('#admins-parent_id').val() === '') {
                parentDropdown.val(admins[0].id).trigger('change');
            }
        } else {
            // Default: Empty
            parentDropdown.append('<option value="">Select Parent</option>');
            parentDropdown.prop('disabled', false);
        }
    }

    // Trigger the updateParentDropdown function on type selection
    $('#admin-type').on('change', function() {
        let type = $(this).val();
        updateParentDropdown(type);  // Update the parent dropdown based on selected type
    });

    // Initialize the parent dropdown on page load based on the initial type
    $(document).ready(function() {
        let initialType = $('#admin-type').val();
        if (initialType) {
            updateParentDropdown(initialType);  // Ensure dropdown is updated on load
        }
    });
JS;

$this->registerJs($script);
?>