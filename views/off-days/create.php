<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\OffDays $model */

$this->title = 'Create Off Days';
$this->params['breadcrumbs'][] = ['label' => 'Off Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="off-days-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
