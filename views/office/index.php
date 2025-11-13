<?php

use app\helpers\AppHelper;
use app\models\Office;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\OfficeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Offices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="office-index">


    <p style="float: right;">
        <?= Html::a('Create Office', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'latitude',
            'longitude',
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                        return '<label class="switch switch-primary">'
                            . Html::checkbox('onoffswitch', $model->is_active, [
                                'class' => "",
                                'id' => "myonoffswitch" . $model->id,
                                'onclick' => 'app.changeStatus("office/activate",this,' . $model->id . ')'
                            ]) .
                            '<span></span>' .
                            '</label>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'is_active', AppHelper::$statusOptions, ['class' => 'form-control select-chosen', 'prompt' => 'Filter By Status']),
            ],
            'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Office $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>