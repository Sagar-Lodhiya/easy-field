<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PartyCategories $model */

$this->title = 'Create Party Categories';
$this->params['breadcrumbs'][] = ['label' => 'Party Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="party-categories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
