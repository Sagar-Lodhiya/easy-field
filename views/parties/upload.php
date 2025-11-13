<?php

use app\models\Units;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RawMaterials $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Excel Upload';
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="parties-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'file')->fileInput(['class' => 'form-control'])->label('Excel File') ?>

        </div>
    </div>


    <div class="form-group" style="float: right;">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center text-warning">Uploading Instructions</h1>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h2 class="text-center text-primary" >Update</h2>

            <ul>
                <li>Export the file: Begin by exporting the file containing the data you want to update.</li>
                <li>Modify values: Once you have the file, open it for editing and make the desired changes to the values.</li>
                <li> Important: Never modify the ID values within the file. IDs are unique identifiers used by the system, and changing them can lead to unintended consequences and errors.</li>
                <li>Upload the file: Upload the modified file back into your project. The system will recognize the existing IDs and update the corresponding elements with your changes.</li>
            </ul>


        </div>

        <div class="col-md-6">

            <h2 class="text-center text-success">Create</h2>

            <ul>
                <li>Export the file: Begin by exporting the file containing the data you want to update.</li>
                <li>Prepare a new Row: Create a new Row at the end that contains the data for the elements you want to create.</li>
                <li>Leave ID empty: Ensure that the ID field is left blank for each new element. The system will automatically assign unique IDs during creation.</li>
                <li>Upload the file: Upload the new file containing your elements. The system will process the file and create new elements based on the provided data.</li>
            </ul>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

</div>