<?php

/** @var yii\web\View $this */

use app\models\Companies;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->id;
?>


<!-- Page content -->
<div id="page-content" style="min-height: 1224px;">
    <div class="content-header">
        <ul class="nav-horizontal text-center">
            <li class="active">
                <a href="page_ecom_dashboard.php"><i class="fa fa-bar-chart"></i> Dashboard</a>
            </li>
            <li>
                <a href="admins"><i class="gi gi-shop_window"></i> Admins</a>
            </li>
            <li>
                <a href="users"><i class="gi gi-shopping_cart"></i>Users</a>
            </li>
            <li>
                <a href="/map"><i class="gi gi-stopwatch sidebar-nav-icon"></i> Live Map</a>
            </li>
            <li>
                <a href="/parties"><i class="gi gi-pencil"></i> Parties</a>
            </li>
            <li>
                <a href="/attendance/active-user"><i class="gi gi-user"></i> Active Users</a>
            </li>
        </ul>
    </div>
    <div class="row text-center">
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background">
                    <h4 class="widget-content-light"><strong>Active</strong> Users</h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 animation-expandOpen"><?= $data['activeUsers'] ?? 0 ?></span></div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background-dark">
                    <h4 class="widget-content-light"><strong>Absent</strong> Users</h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 themed-color-dark animation-expandOpen"><?= $data['absentUsers'] ?? 0 ?></span></div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background-warning">
                    <h4 class="widget-content-light"><strong>Pending</strong> Expense</h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 themed-color-dark animation-expandOpen"><?= $data['pendingExpense'] ?? 0 ?></span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="javascript:void(0)" class="widget widget-hover-effect2">
                <div class="widget-extra themed-background-success">
                    <h4 class="widget-content-light"><strong>Total</strong> Parties</h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 themed-color-dark animation-expandOpen"><?= $data['totalParties'] ?? 0 ?></span></div>
            </a>
        </div>
    </div>
    <div class="block full">
        <div class="block-title">
            <div class="block-options pull-right">
                <div class="btn-group btn-group-sm">
                    <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle"
                        data-toggle="dropdown">Last Year <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="active">
                            <a href="javascript:void(0)">Last Year</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Last Month</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">This Month</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Today</a>
                        </li>
                    </ul>
                </div>
                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title=""
                    data-original-title="Settings"><i class="fa fa-cog"></i></a>
            </div>
            <h2><strong>Analytics</strong> </h2>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="row push">
                    <div class="col-xs-6">
                        <h3><strong class="animation-fadeInQuick"><?= $data['totalUsers'] ?? 0 ?></strong><br><small
                                class="text-uppercase animation-fadeInQuickInv"><a href="users">Total Users</a></small>
                        </h3>
                    </div>
                    <div class="col-xs-6">
                        <h3><strong class="animation-fadeInQuick">₹
                                <?= $data['pendingExpenseAmount'] ?? 0 ?></strong><br><small
                                class="text-uppercase animation-fadeInQuickInv"><a href="javascript:void(0)">Pending
                                    Expense</a></small></h3>
                    </div>
                    <div class="col-xs-6">
                        <h3><strong class="animation-fadeInQuick"><?= $data['totalVisits'] ?? 0 ?></strong><br><small
                                class="text-uppercase animation-fadeInQuickInv"><a href="visits">Total
                                    Visits</a></small></h3>
                    </div>

                    <div class="col-xs-6">
                        <h3><strong class="animation-fadeInQuick">₹
                                <?= $data['totalPayments'] ?? 0 ?></strong><br><small
                                class="text-uppercase animation-fadeInQuickInv"><a href="payments">Total
                                    Payments</a></small></h3>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-lg-8">
                <div id="chart-overview" style="height: 350px; padding: 0px; position: relative;"><canvas
                        class="flot-base" width="1071" height="350"
                        style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1071px; height: 350px;"></canvas>
                    <div class="flot-text"
                        style="position: absolute; inset: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                        <div class="flot-x-axis flot-x1-axis xAxis x1Axis"
                            style="position: absolute; inset: 0px; display: block;">
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 28px; text-align: center;">
                                Jan</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 120px; text-align: center;">
                                Feb</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 212px; text-align: center;">
                                Mar</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 305px; text-align: center;">
                                Apr</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 397px; text-align: center;">
                                May</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 492px; text-align: center;">
                                Jun</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 587px; text-align: center;">
                                Jul</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 676px; text-align: center;">
                                Aug</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 769px; text-align: center;">
                                Sep</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 863px; text-align: center;">
                                Oct</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 954px; text-align: center;">
                                Nov</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; max-width: 89px; top: 335px; left: 1048px; text-align: center;">
                                Dec</div>
                        </div>
                        <div class="flot-y-axis flot-y1-axis yAxis y1Axis"
                            style="position: absolute; inset: 0px; display: block;">
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; top: 323px; left: 25px; text-align: right;">0</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; top: 244px; left: 6px; text-align: right;">5000</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; top: 165px; left: 0px; text-align: right;">10000</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; top: 86px; left: 0px; text-align: right;">15000</div>
                            <div class="flot-tick-label tickLabel"
                                style="position: absolute; top: 7px; left: 0px; text-align: right;">20000</div>
                        </div>
                    </div><canvas class="flot-overlay" width="1071" height="350"
                        style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 1071px; height: 350px;"></canvas>
                    <div class="legend">
                        <div
                            style="position: absolute; width: 86px; height: 56px; top: 24px; left: 51px; background-color: rgb(255, 255, 255); opacity: 0.85;">
                        </div>
                        <table style="position:absolute;top:24px;left:51px;;font-size:smaller;color:#545454">
                            <tbody>
                                <tr>
                                    <td class="legendColorBox">
                                        <div style="border:1px solid #ccc;padding:1px">
                                            <div
                                                style="width:4px;height:0;border:5px solid rgb(27,186,225);overflow:hidden">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="legendLabel">Earnings</td>
                                </tr>
                                <tr>
                                    <td class="legendColorBox">
                                        <div style="border:1px solid #ccc;padding:1px">
                                            <div
                                                style="width:4px;height:0;border:5px solid rgb(51,51,51);overflow:hidden">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="legendLabel">Orders</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div class="block-title">
                    <h2><strong>Latest</strong> Expense</h2>
                </div>
                <table class="table table-borderless table-striped table-vcenter table-bordered">
                    <tbody>
                        <?php foreach ($data['expenseDetails'] as $expense): ?>
                            <tr>
                                <td class="text-center" style="width: 100px;">
                                    <a
                                        href="javascript:void(0)"><strong><strong><?= htmlspecialchars($expense['expense_details']) ?? '' ?></strong></a>
                                </td>

                                <td class="hidden-xs">
                                    <a href="javascript:void(0)"><?= htmlspecialchars($expense['category_name']) ?? '' ?></a>
                                </td>
                                <td class="hidden-xs">
                                    <?= htmlspecialchars($expense['city_name']) ?? '' ?>
                                </td>
                                <td class="text-right">
                                    <strong>$<?= number_format($expense['requested_amount'], 2) ?? '' ?></strong>
                                </td>
                                <td class="text-right">
                                    <?php
                                    $labelClass = '';
                                    switch ($expense['status']) {
                                        case 'Requested':
                                            $labelClass = 'label label-warning'; // Yellow for Requested
                                            break;
                                        case 'Pending':
                                            $labelClass = 'label label-primary'; // Blue for Pending (instead of green)
                                            break;
                                        case 'Partially Approved':
                                            $labelClass = 'label label-info'; // Light blue for Partially Approved
                                            break;
                                        case 'Approved':
                                            $labelClass = 'label label-success'; // Green for Approved
                                            break;
                                        case 'Rejected':
                                            $labelClass = 'label label-danger'; // Red for Rejected
                                            break;
                                    }
                                    ?>
                                    <span class="<?= $labelClass ?>"><?= htmlspecialchars($expense['status']) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>