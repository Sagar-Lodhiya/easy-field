<?php

use app\helpers\PermissionHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\EmployeeGrades $model */

$this->title = $employeeGrade->name;
$this->params['breadcrumbs'][] = ['label' => 'Employee Grades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('employee-grades');
$permission = $permissionData['permissions'];

\yii\web\YiiAsset::register($this);
?>
<div class="employee-grades-view">

    <p style="float: right;">
        <?= ($permission['update']) ? Html::a('Update', ['update', 'id' => $employeeGrade->id], ['class' => 'btn btn-primary']) :'' ?>
        <?= ($permission['delete']) ? Html::a('Delete', ['delete', 'id' => $employeeGrade->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : '' ?>
    </p>

    <div class="row">
        <div class="col-md-12">


            <?= DetailView::widget([
                'model' => $employeeGrade,
                'attributes' => [
                    'name',
                    'created_at',
                ],
            ])
            ?>
        </div>

        <div class="col-md-12">
            <?php
            foreach ($model->grades as $grade) {
            ?>

                <fieldset class="fieldset">
                    <legend>Grade: <?= Html::encode($grade['type']) ?></legend>

                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">City Grade</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grade['categories'] as $category) { ?>

                                    <tr>
                                        <th scope="row">3</th>
                                        <td><?= $category['category_name'] ?></td>
                                        <td><?= $category['amount'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </fieldset>

            <?php
            }
            ?>
        </div>

    </div>

</div>