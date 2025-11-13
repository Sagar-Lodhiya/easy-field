<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AuthRoles $model */
/** @var app\models\AuthModules $authModules */

$this->title = 'Create Auth Roles';
$this->params['breadcrumbs'][] = ['label' => 'Auth Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-roles-create">

    <?= $this->render('_form', [
        'model' => $model,
        'authModules' => $authModules,
    ]) ?>

</div>
