<?php

use app\helpers\PermissionHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategories $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('expense-categories');
$permission = $permissionData['permissions'];

\yii\web\YiiAsset::register($this);
?>
<div class="expense-categories-view">

    <p style="float: right;">
        <?= ($permission['update']) ?  Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= ($permission['delete']) ? Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'name',
            'type',
            'created_at',
        ],
    ]) ?>

</div>
