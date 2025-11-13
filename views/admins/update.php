<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Admins */

$this->title = 'Update Admins: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admins-update">
    <div class="row">
        <div class="col-lg-12">
            <div class="hpanel">
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>