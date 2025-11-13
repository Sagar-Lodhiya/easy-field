<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Leave $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Leaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="leave-view">



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
            'leave_type_id',
            'user_id',
            'start_date',
            'end_date',
            'reason:ntext',
            'created_at',
            'updated_at',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                switch ($model->status) {
                    case 1:
                        return '<span class="label label-warning">Pending</span>';
                    case 2:
                        return '<span class="label label-success">Approved</span>';
                    case 3:
                        return '<span class="label label-danger">Declined</span>';
                    default:
                        return '<span class="label label-default">Unknown</span>';
                }
            }
            ],
        ],
    ]) ?>
    <?php if ($model->status == 1): // Pending ?>
        <div id="form-field" class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-title">
                        <h2><strong>Status</strong> Update</h2>
                    </div>
                    <?php $form = ActiveForm::begin([
                        'action' => ['leave/update-status'],
                        'options' => ['class' => 'status-form form-horizontal']
                    ]); ?>

                    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
                    <div class="form-group">
                        <div class="col-md-6" style="margin-left: 25%">
                            <?= $form->field($model, 'status')->dropDownList([
                                '' => 'Select status',
                                2 => 'Approved',
                                3 => 'Declined'
                            ], ['class' => 'form-control']) ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-md-9" style="margin-left: 24%">
                            <?= Html::submitButton("Submit", ['class' => "btn btn-sm btn-primary"]) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>