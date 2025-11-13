<?php

use app\helpers\AppHelper;
use app\helpers\PermissionHelper;
use app\models\CityGrades;
use app\models\Grades;
use app\models\States;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\CityGradesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'City Grades';
$this->params['breadcrumbs'][] = $this->title;

// Get user permissions for this controller
$permissionData = PermissionHelper::getUserPermissionsString('city-grades');
$permission = $permissionData['permissions'];

?>
<div class="city-grades-index">

    <p style="float: right;">
        <?= ($permission['create']) ?  Html::a('Create City Grades', ['create'], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'city',
            [
                'attribute' => 'grade_id',
                'format' => 'raw',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'grade_id',
                    ArrayHelper::map(Grades::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name'),
                    ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Grade']
                ),
                'value' => function ($model) {
                    return $model->grade ? $model->grade->name : 'N/A';
                }
            ],



            [
                'attribute' => 'state_id',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, 'state_id', States::getStatesList(), ['class' => 'form-control select-chosen', 'prompt' => 'Filter By State']),
                'value' => function ($model) {
                    return $model->state->name;
                }
            ],
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="switch switch-primary">'
                        . Html::checkbox('onoffswitch', $model->is_active, [
                            'class' => "",
                            'id' => "myonoffswitch" . $model->id,
                            'onclick' => 'app.changeStatus("city-grades/activate",this,' . $model->id . ')'
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