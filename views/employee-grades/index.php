<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\EmployeeGrades;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EmployeeGradesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Employee Grades';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('employee-grades');
$permission = $permissionData['permissions'];

?>
<div class="employee-grades-index">

    <p style="float: right;">
        <?= ($permission['create']) ? Html::a('Create Employee Grades', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("employee-grades/activate",this,' . $model->id . ')'
                        ]) .
                        '<span></span>' .
                        '</label>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
            ],
            'created_at',
            [
                'class' => ActionColumn::class,
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#1bbae1;'],
                'visibleButtons' => [
                    'update' => $permission['update'],
                    'delete' => $permission['delete'],
                    'view' => $permission['view'],
                ],
            ],
        ],
    ]); ?>


</div>
