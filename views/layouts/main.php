<?php

use app\assets\AppAsset;
use app\assets\ThemeAsset;
use app\widgets\Alert;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>

<?= $this->render('includes/admin_header') ?>
<div class="content-header">
    <div class="header-section" style="padding-left: 30px;">
        <div class="col-md-3" style="position: absolute;right: 0px">
            <?= Alert::widget() ?>
        </div>
        <h1>
            <i class="gi gi-charts"></i> <?= $this->title ?><br><small></small>
        </h1>
    </div>
</div>
<div id="hbreadcrumb" class="pull-right">
    <?php
    echo
    Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        'tag' => 'ol',
        'options' => [
            'class' => 'hbreadcrumb breadcrumb'
        ]
    ])
    ?>
</div>
<div class="row full-height" style=" margin-left: 2px; margin-right: 2px; background-color: white !important;">
    <!--    --><? //= Alert::widget() 
                ?>
    <div class="col-md-12">
        <?php $this->beginBody() ?>

        <?= $content ?>
    </div>
</div>
<?= $this->render('includes/admin_footer') ?>