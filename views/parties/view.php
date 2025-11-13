<?php

use app\helpers\PermissionHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Parties $model */

$this->title = $model->firm_name;
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('parties');
$permission = $permissionData['permissions'];

\yii\web\YiiAsset::register($this);
?>
<div class="parties-view">

    <p style="float: right;"> 
        <?= ($permission['update']) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= ($permission['update']) ? Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'employee.name',
            'partyCategory.name',
            'dealer_name',
            'dealer_phone',
            'firm_name',
            'address:ntext',
            'city_or_town',
            'gst_number',
            'dealer_aadhar',
            'created_at',
        ],
    ]) ?>

</div>
