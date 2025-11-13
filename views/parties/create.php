<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Parties $model */

$this->title = 'Create Parties';
$this->params['breadcrumbs'][] = ['label' => 'Parties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parties-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
