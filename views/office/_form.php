<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Office $model */
/** @var yii\widgets\ActiveForm $form */
?>

<style>
    .custom-control.custom-switch {
        display: flex;
        align-items: center;
        position: relative;
        padding-left: 2.5rem;
    }

    .custom-control-input {
        position: absolute;
        left: -9999px;
        top: -9999px;
    }

    .custom-control-input:checked~.custom-control-label::before {
        background-color: #4caf50;
        border-color: #4caf50;
    }

    .custom-control-label {
        position: relative;
        display: block;
        width: 34px;
        height: 20px;
        background-color: #ddd;
        border: 1px solid #ddd;
        border-radius: 34px;
        cursor: pointer;
    }

    .custom-control-label::before {
        content: '';
        position: absolute;
        top: 1px;
        left: 1px;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border-radius: 50%;
        transition: all 0.3s;
    }

    .custom-control-input:checked~.custom-control-label::before {
        transform: translateX(14px);
    }

    .switch-field {
        margin-bottom: 20px;
    }

    .switch-field label {
        margin-bottom: 5px;
        display: block;
        font-weight: bold;
    }

    .error {
        border-color: #dc3545 !important;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #3c763d;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }

    .location-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .location-section h5 {
        margin-bottom: 15px;
        color: #495057;
    }
</style>

<div class="office-form">

    <?php $form = ActiveForm::begin(['id' => 'office-form']); ?>

    <!-- Office Name Section -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Enter office name (e.g., Codeflix)']) ?>
        </div>
        <div class="col-md-6">
            <!-- Empty column to maintain layout -->
        </div>
    </div>

    <!-- Location Section -->
    <div class="location-section">
        <h5><i class="fa fa-map-marker"></i> Office Location</h5>
        <p class="text-muted">Search for a location on the map to set the office coordinates</p>
        
        <div id="location-info" style="margin-top: 15px; padding: 10px; background-color: #fff; border-radius: 4px; display: none; border: 1px solid #dee2e6;">
            <h6>Selected Location:</h6>
            <p id="selected-address">No location selected</p>
            <p><strong>Coordinates:</strong> <span id="selected-coords">-</span></p>
        </div>

        <div class="form-group">
            <label class="control-label">Location Search</label>
            <input
                id="location-search-input"
                class="form-control"
                type="text"
                placeholder="Search for a location (e.g., Ahmedabad, Mumbai, etc.)..."
                style="margin-bottom: 10px;" />
            <!-- Map Container -->
            <div id="map" style="height: 300px; width: 100%; border: 1px solid #ccc; border-radius: 4px;"></div>
        </div>
    </div>

    <!-- Hidden Latitude and Longitude fields -->
    <?= $form->field($model, 'latitude')->hiddenInput(['id' => 'office-latitude'])->label(false) ?>
    <?= $form->field($model, 'longitude')->hiddenInput(['id' => 'office-longitude'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- Google Maps JS API with Places library -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS1kzHr_O2Qg6cac3qn2X8WhdqhADAiRc&callback=initAutocomplete&libraries=places&v=weekly"
    defer>
</script>

<?php
$this->registerCssFile('@web/css/distance.css');
$this->registerJsFile('@web/js/office-distances.js', ['depends' => [yii\web\JqueryAsset::class]]);
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing office form handlers');
    
    // Enhanced form validation
    const form = document.getElementById('office-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            let isValid = true;
            const requiredFields = ['name', 'latitude', 'longitude'];
            
            requiredFields.forEach(function(fieldName) {
                const field = document.querySelector(`[name="Office[${fieldName}]"]`);
                if (field && !field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    console.log(`Field ${fieldName} is required but empty`);
                } else if (field) {
                    field.classList.remove('error');
                    console.log(`Field ${fieldName} has value: ${field.value}`);
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please enter an office name and select a location on the map before saving.');
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            return true;
        });
    }

    // Add click handler to map to allow manual location selection
    const map = document.getElementById('map');
    if (map) {
        map.addEventListener('click', function(e) {
            console.log('Map clicked, this will be handled by Google Maps API');
        });
    }
});
</script>