<?php

use app\helpers\PermissionHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\LeaveType $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Leave Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('leave-type');
$permission = $permissionData['permissions'];

\yii\web\YiiAsset::register($this);
?>
<div class="leave-type-view">
 

    <p>
        <?= ($permission['update']) ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) :'' ?>
        <?= ($permission['update']) ? Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) :'' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'leave_type',
        ],
    ]) ?>

</div>
