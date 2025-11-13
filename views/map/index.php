<?php

/** @var yii\web\View $this */

use app\assets\MapAsset;

MapAsset::register($this);

$this->title = 'Live Map';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="map-index" style="height: 90vh;">
    <div id="map" style="height: 100%; width: auto;"></div>
</div>
<?php

$this->registerJs('
    fetchData()
    setInterval(fetchData, 60000);
', \yii\web\View::POS_READY);

?>