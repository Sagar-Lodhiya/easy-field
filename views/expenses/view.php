<?php

use app\helpers\AppHelper;
use app\models\ExpenseStatuses;
use yii\helpers\BaseUrl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Expenses $model */

$this->title = $model->user->name . ' ' . $model->user->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="expenses-view">
    <div class="row text-center">
        <div class="col-sm-6 col-lg-3">
            <div class="widget">
                <div class="widget-extra themed-background">
                    <h4 class="widget-content-light"><strong><?= $this->title ?></strong></h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 text-primary animation-expandOpen"><?= $model->created_at ?></span></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="widget">
                <div class="widget-extra themed-background-success">
                    <h4 class="widget-content-light"><i class="fa fa-paypal"></i> <strong>Amount</strong></h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 text-success animation-expandOpen"><?= 'Rs. ' . $model->requested_amount ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="widget">
                <div class="widget-extra themed-background-warning">
                    <h4 class="widget-content-light"><i class="fa fa-archive"></i> <strong>Category</strong></h4>
                </div>
                <div class="widget-extra-full"><span class="h2 text-warning"><?= $model->category->name ?></span></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="widget">
                <div class="widget-extra themed-background-muted">
                    <h4 class="widget-content-light"><i class="fa fa-truck"></i> <strong>Status</strong></h4>
                </div>
                <div class="widget-extra-full"><span
                        class="h2 text-muted animation-pulse"><?= $model->status->name ?></span></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="block">
                <div class="block-title">
                    <h2><i class="fa fa-file-o"></i> <strong>Expense </strong> Info</h2>
                </div>
                <div class="block-section text-center">
                    <a href="javascript:void(0)">
                        <img src="<?= $model->expense_photo ?>" height="auto" width="100%" alt="avatar"
                            class="img-rounded">
                    </a>
                    <h3>
                        <strong>Jonathan Taylor</strong><br><small></small>
                    </h3>
                </div>
                <table class="table table-borderless table-striped table-vcenter">
                    <tbody>
                        <tr>
                            <td class="text-right" style="width: 50%;"><strong>Requested Amount</strong></td>
                            <td><?= 'Rs ' . $model->requested_amount ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Approved Amount</strong></td>
                            <td><?= 'Rs ' . $model->approved_amount ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>City</strong></td>
                            <td><?= ($model->city_id && $model->city) ? $model->city->city : '' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Is Night Stay</strong></td>
                            <td><?= $model->is_night_stay ? '<span class="label label-primary">Yes</span>' : '<span class="label label-warning">No</span>' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Expense Type</strong></td>
                            <td><?= $model->expense_type == 'fixed' ? '<span class="label label-success">Fixed</span>' : '<span class="label label-secondary">Claimed</span>' ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"><strong>Details</strong></td>
                            <td><?= $model->expense_details ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block">
                <div class="block-title">
                    <h2><i class="fa fa-form"></i> <strong>Update Expense</strong></h2>
                </div>
                <table class="table table-bordered table-striped table-vcenter">
                    <?php
                    // Get the logged-in admin ID
                    $loggedInAdminId = Yii::$app->user->identity->id;

                    // Check if the expense has been processed by any parent
                    $isProcessedByParent = array_filter($model->expenseApprovalProcesses, function ($item) use ($model) {
                        $parents = $model->user->getParents();
                        $parentIds = array_map(function ($parent) {
                            return $parent->id;
                        }, $parents);
                        return in_array($item->admin_id, $parentIds);
                    });

                    // Check if the logged-in admin can process the request
                    $isAuthorized = $loggedInAdminId == $model->admin_id || (!$isProcessedByParent && in_array($loggedInAdminId, array_map(function ($parent) {
                        return $parent->id;
                    }, $model->user->getParents())));

                    if ($isAuthorized) {
                        // Allow updating the expense
                        $form = ActiveForm::begin([
                            'options' => [
                                'class' => 'status-form form-horizontal',
                                'id' => 'expense-status-form',
                                'data-pjax' => true, // Enable PJAX submission
                                'method' => 'post',
                            ]
                        ]);
                        ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-6" style="margin-left: 25%">
                                    <?= $form->field($statusModel, 'status')->dropDownList(ExpenseStatuses::getExpenseStatusList(), ['class' => 'form-control', 'id' => 'status-dropdown']); ?>
                                </div>

                                <div class="form-group" id="amount-field" style="display: none;">
                                    <div class="col-md-6" style="margin-left: 25%">
                                        <?= $form->field($statusModel, 'amount'); ?>
                                    </div>
                                </div>
                                <div class="form-group" id="reason-field" style="display: none;">
                                    <div class="col-md-6" style="margin-left: 25%">
                                        <?= $form->field($statusModel, 'reason'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-actions">
                                <div class="col-md-9 " style="margin-left: 24%">
                                    <?= Html::submitButton("Submit", ['class' => "btn btn-sm btn-primary"]); ?>
                                </div>
                            </div>

                            <?php
                            $this->registerJs(<<<JS
                           $(document).ready(function() {
                               // On change of the status dropdown
                               $('#status-dropdown').on('change', function() {
                                   var selectedStatus = $(this).val();
                                   console.log("Selected Status: " + selectedStatus); // Debugging log
                           
                                   // Show amount and remarks if partially approved (ID 3), hide otherwise
                                   if (selectedStatus == 3) { // Assuming 3 is the ID for "Partially Approved"
                                       $('#amount-field').show();
                                       $('#reason-field').show();
                                   } else {
                                       $('#amount-field').hide();
                                       $('#reason-field').hide();
                                   }
                               });
                           
                               // Trigger change event to set initial state
                               $('#status-dropdown').trigger('change');
                           });
                           JS
                            );

                            ?>

                        </div>
                        <?php ActiveForm::end(); ?>
                        <?php
                    } else {
                        // If the expense is already processed by a parent, show the status without updating
                        $myStatus = array_filter($model->expenseApprovalProcesses, function ($item) use ($loggedInAdminId) {
                            return $item->admin_id == $loggedInAdminId;
                        });

                        if (!empty($myStatus)) {
                            $myStatus = array_values($myStatus); // Re-index the array
                            ?>
                            <tbody>
                                <tr>
                                    <td style="width: 20%;"><strong>Status</strong></td>
                                    <td><?= $myStatus[0]->expenseStatus->name ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Approved Amount</strong></td>
                                    <td>Rs <strong><?= $myStatus[0]->approved_amount ?></strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Remark</strong></td>
                                    <td><?= $myStatus[0]->approved_reason ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Date</strong></td>
                                    <td><?= date('d/m/Y - H:i', strtotime($myStatus[0]->created_at)) ?></td>
                                </tr>
                            </tbody>
                            <?php
                        } else {
                            echo '<tr><td colspan="2">Parent Admin Already Processed the Expense Status.</td></tr>';
                        }
                    }
                    ?>

                </table>
            </div>
        </div>


        <div class="col-md-6">
            <div class="block">
                <div class="block-title">
                    <div class="block-options pull-right">
                        <span class="label label-success"><strong>Rs <?= $model->approved_amount ?></strong></span>
                    </div>
                    <h2><i class="fa fa-truck"></i> <strong>Approval Process</strong>
                        (<?= count($model->expenseApprovalProcesses) ?>)</h2>
                </div>
                <table class="table table-bordered table-striped table-vcenter">
                    <tbody>
                        <?php foreach ($model->getExpenseApprovalProcesses()->orderBy(['id' => SORT_DESC])->all() as $key => $approvalProcess) { ?>
                            <tr>
                                <td class="text-center"><strong><?= $approvalProcess->admin->name ?></strong></td>
                                <td class="text-center" style="width: 100px;"><strong>Rs
                                        <?= $approvalProcess->approved_amount ?></strong></td>
                                <td><span class="label label-warning"><?= $approvalProcess->expenseStatus->name ?></span>
                                </td>
                                <td class="hidden-xs text-center">
                                    <?= date('d/m/Y - H:i', strtotime($approvalProcess->created_at)) ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>


</div>
</div>