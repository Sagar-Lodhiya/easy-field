<?php

/** @var yii\web\View $this */

use app\assets\TreeAsset;

TreeAsset::register($this);
$this->title = 'Tree View';
$this->params['breadcrumbs'][] = $this->title;


?>

<div id="tree"></div>

<?php

$this->registerJs('

    chart.load('.$model.');


', \yii\web\View::POS_READY);

?>