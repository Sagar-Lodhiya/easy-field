<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\States $model */

$this->title = 'Create States';
$this->params['breadcrumbs'][] = ['label' => 'States', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="states-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
