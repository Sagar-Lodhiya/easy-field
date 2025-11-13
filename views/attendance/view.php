<?php

use app\assets\RouteAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Notifications $model */

$this->title = $model->user->name . ' ' . $model->user->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Atendance Detail', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
RouteAsset::register($this);
?><div class="row">
    <div class="col-lg-4">
        <div class="block">
            <div class="block-title">
                <h2><i class="fa fa-file-o"></i> <strong>Customer</strong> Info</h2>
            </div>
            <div class="block-section text-center">
                <a href="javascript:void(0)">
                    <img src="<?= !empty($model->user->profile) ?  $model->user->profile :  '../theme/img/userAvatar.png' ?>" alt="User Avatar"
                        class="img-circle"
                        height="80px"
                        width="80px">

                </a>
                <br>
                <h3>
                    <td class="text-right" style="width: 50%;">Name:</td>
                    <strong><?=
                            $model->user->name . ' ' . $model->user->last_name;
                            ?></strong>
                </h3>
            </div>
            <table class="table table-borderless table-striped table-vcenter">
                <tbody>
                    <tr>
                        <td class="text-right" style="width: 50%;"><strong>Employee Grade</strong></td>
                        <td><?= $model->user->employeeGrade->name; ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Phone No</strong></td>
                        <td><?=
                            $model->user->phone_no;
                            ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Vehical Type</strong></td>
                        <td><?=
                            $model->vehicle_type;
                            ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><strong>Date</strong></td>
                        <td>
                            <?=
                            date('d-m-Y', strtotime($model->created_at));
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="block">
            <div class="block-title">
                <h2><i class="fa fa-line-chart"></i> <strong>Quick</strong> Stats</h2>
            </div>


            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                <div class="widget-simple text-center">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-md-6 text-center">
                            <p class="font-weight-bold">Punch In Image</p>
                            <img src="<?= $model->punch_in_image ?>"
                                alt="Punch In Image" class="rounded shadow border"
                                style="width: 120px; height: 120px; object-fit: cover; border-radius: 8%;">
                        </div>
                        <div class="col-md-6 text-center">
                            <p class="font-weight-bold">Punch Out Image</p>
                            <img src="<?= $model->punch_out_image ?>"
                                alt="Punch Out Image" class="rounded shadow border"
                                style="width: 120px; height: 120px; object-fit: cover; border-radius: 8%;">
                        </div>
                    </div>
                </div>
            </a>

            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                <div class="widget-simple">
                    <div class="widget-icon pull-right themed-background">
                        <i class="fa fa-truck"></i>
                    </div>
                    <h4 class="text-left">
                        <strong><?=
                                $model->total_distance;
                                ?></strong><br><small>Traveled KM</small>
                    </h4>
                </div>
            </a>
            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                <div class="widget-simple">
                    <div class="widget-icon pull-right themed-background-success">
                        <i class="fa fa-usd"></i>
                    </div>
                    <h4 class="text-left text-success">
                        <strong><?= date_diff(date_create($model->updated_at), date_create($model->created_at))->format('%h Hours and %m Minutes and %s Seconds') ?></strong><br><small>Duration</small>
                    </h4>
                </div>
            </a>
            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">

            </a>
            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                <div class="widget-simple">
                    <div class="widget-icon pull-right themed-background-info">
                        <i class="fa fa-money"></i>
                    </div>
                    <h4 class="text-left text-info">
                        <strong>
                            <?=
                            $model->da;
                            ?>

                        </strong><br><small>DA</small>
                    </h4>
                </div>
            </a>
            <a href="javascript:void(0)" class="widget widget-hover-effect2 themed-background-muted-light">
                <div class="widget-simple">
                    <div class="widget-icon pull-right themed-background-danger">
                        <i class="fa fa-heart"></i>
                    </div>
                    <h4 class="text-left text-danger">
                        <strong>

                            <?=
                            $model->ta;
                            ?>

                        </strong><br><small>TA</small>
                    </h4>
                </div>
            </a>

        </div>
    </div>
    <div class="col-lg-8">
        <div id="map" style="height: 75vh; width: auto;"></div>
    </div>
</div>

<?php

$this->registerJs('
    getPins(' . $model->id . ');
', \yii\web\View::POS_READY);

?>