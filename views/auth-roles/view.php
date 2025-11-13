<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AuthRoles $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Auth Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<style>
    /* Custom checkbox styling */
    .blue-checkbox {
        appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #007bff; /* Blue border */
        border-radius: 3px;
        position: relative;
        cursor: not-allowed;
        background-color: transparent;
    }

    .blue-checkbox:checked {
        background-color: #007bff; /* Blue background */
        border-color: #007bff;
    }

    .blue-checkbox:checked::after {
        content: '✔';
        color: white;
        font-size: 12px;
        font-weight: bold;
        position: absolute;
        left: 2px;
        top: -1px;
    }
</style>

<div class="auth-roles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'is_active',
            'is_deleted',
        ],
    ]) ?>

    <?php if (!empty($model->authPermissions)) : ?>
        <h3>Permissions</h3>
        <div class="permissions-list">
            <?php 
            $groupedPermissions = [];
            
            // Group permissions by module
            foreach ($model->authPermissions as $permission) {
                $moduleName = $permission->authItems->authModules->name ?? 'Unknown Module';
                $groupedPermissions[$moduleName][] = $permission;
            }
            ?>

            <ul>
                <?php foreach ($groupedPermissions as $module => $permissions): ?>
                    <li><strong><?= Html::encode($module) ?></strong></li>
                    <ul>
                        <?php foreach ($permissions as $permission): ?>
                            <li>
                                <input type="checkbox" class="blue-checkbox" <?= $permission ? 'checked' : '' ?> disabled>
                                <?= Html::encode($permission->authItems->name) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else : ?>
        <p>No permissions assigned.</p>
    <?php endif; ?>

</div>
