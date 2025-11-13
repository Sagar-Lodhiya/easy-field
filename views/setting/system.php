<?php
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SystemSettingForm $model */

$this->title = 'System Setting';
$this->params['breadcrumbs'][] = $this->title;
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
</style>
<div class="system">
    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'employee_add_limit')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'sub_admin_add_limit')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'traveling_rate_car')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'traveling_rate_bike')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'ping_interval')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'app_version')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'under_maintenance')->textInput(['maxlength' => true]) ?>
        </div>


    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group profile_image">
                <label class="control-label" style="display: block;">
                    Profile Image
                </label>
                <?php
                echo \dosamigos\fileupload\FileUpload::widget([
                    'name' => 'Tutorial[file]',
                    'url' => [
                        '/upload/common?attribute=Tutorial[file]'
                    ],
                    'options' => [
                        'accept' => 'video/*',
                    ],
                    'clientOptions' => [
                        'dataType' => 'json',
                        'maxFileSize' => 2000000,
                    ],
                    'clientEvents' => [
                        'fileuploadprogressall' => "function (e, data) {
                                        var progress = parseInt(data.loaded / data.total * 100, 10);
                                        $('#progress_0').show();
                                        $('#progress_0 .progress-bar').css(
                                            'width',
                                            progress + '%'
                                        );
                                     }",
                        'fileuploaddone' => 'function (e, data) {
                                        if(data.result.files.error==""){
                                            var img = \'<br/> <video width="180" height="240" controls><source src="' . yii\helpers\BaseUrl::home() . 'uploads/\'+data.result.files.name+\'" type="video/mp4"></video>\';
                                            $("#tutorial_preview").html(img);
                                            $("#systemsettingform-video_tutorial").val(data.result.files.name);
                                            $("#systemsettingform-video_tutorial").blur();
                                            $("#progress_0 .progress-bar").attr("style","width: 0%;");
                                            $("#progress_0").hide();
                                        }
                                        else{
                                           $("#progress_0 .progress-bar").attr("style","width: 0%;");
                                           $("#progress_0").hide();
                                           var errorHtm = \'<span style="color:#dd4b39">\'+data.result.files.error+\'</span>\';
                                           $("#tutorial_preview").html(errorHtm);
                                           setTimeout(function(){
                                               $("#tutorial_preview span").remove();
                                           },3000)
                                        }
                                    }',
                    ],
                ]);
                ?>
            </div>
            <div id="progress_0" class="progress m-t-xs full progress-small" style="display: none;">
                <div class="progress-bar progress-bar-success"></div>
            </div>

            <div class="col-md-12">
                <label class="control-label">Force Update</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="forceUpdateToggle"
                        <?= $model->is_force_update ? 'checked' : '' ?>>
                    <label class="custom-control-label" for="forceUpdateToggle"></label>
                </div>
                <?= Html::hiddenInput('SystemSettingForm[is_force_update]', $model->is_force_update, ['id' => 'hiddenForceUpdate']) ?>
            </div>
             
                <div id="tutorial_preview">
                <?php if (!empty($model->video_tutorial)): ?>
                    <br />
                    <video width="180" height="240" controls>
                        <source src="<?php echo BaseUrl::home() . 'uploads/' . $model->video_tutorial ?>"
                            type="video/mp4">
                    </video>
                <?php endif; ?>
            </div>
            <?php echo $form->field($model, 'video_tutorial')->hiddenInput()->label(false); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="location-info" style="margin-top: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 4px; display: none;">
                <h5>Selected Location:</h5>
                <p id="selected-address">No location selected</p>
                <p><strong>Coordinates:</strong> <span id="selected-coords">-</span></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">Location Search</label>
                <input
                    id="pac-input"
                    class="form-control"
                    type="text"
                    placeholder="Search for a location..."
                    style="margin-bottom: 10px;" />
                <!-- Map Container -->
                <div id="map" style="height: 300px; width: 100%; border: 1px solid #ccc;"></div>
            </div>
            <!-- Hidden fields to store location data -->
            <?= $form->field($model, 'location')->hiddenInput()->label(false) ?>
            <?= Html::hiddenInput('latitude', '', ['id' => 'latitude']) ?>
            <?= Html::hiddenInput('longitude', '', ['id' => 'longitude']) ?>
            <?= Html::hiddenInput('formatted_address', '', ['id' => 'formatted_address']) ?>
        </div>
    </div>

    <!-- Save button moved to the end -->
    <div class="form-group" style="float:right;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <!-- Google Maps JS API with Places library -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS1kzHr_O2Qg6cac3qn2X8WhdqhADAiRc&callback=initAutocomplete&libraries=places&v=weekly"
        defer>
    </script>
    <?php
    $this->registerCssFile('@web/css/distance.css');
    $this->registerJsFile('@web/js/distance.js', ['depends' => [yii\web\JqueryAsset::class]]);
    ?>
    <?php ActiveForm::end(); ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('forceUpdateToggle');
        const hiddenField = document.getElementById('hiddenForceUpdate');

        toggle.addEventListener('change', () => {
            hiddenField.value = toggle.checked ? 1 : 0;
        });
    });


</script>
