<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class ExpenseStatusForm extends Model
{
    public $amount;
    public $status;
    public $reason;
    public $expense_id;

    public $_expense;

    /**
     * Initialize default values for grades
     */
    public function init()
    {
        parent::init();
    }

    // Define the validation rules
    public function rules()
    {
        return [
            // Validate grades array
            ['status', 'required'],
            [
                ['amount', 'reason'],
                'required',
                'when' => function ($model) {
                    return $model->status == 3;
                }
            ],
            ['amount', 'validateAmount'],
            ['status', 'validateParentProcessed']

        ];
    }

    public function setExpense($expense_id)
    {
        $this->_expense = Expenses::findOne($expense_id);

        if ($this->_expense === null) {
            throw new \InvalidArgumentException('Invalid expense ID provided.');
        }
    }

    public function validateParentProcessed($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $expense = $this->_expense;
            if (!$expense || (float)$expense->requested_amount < (float)$this->amount) {
                $maxAmount = $expense->requested_amount;
                $this->addError($attribute, "Expense already processed by parent");
            }
        }
    }
    public function validateAmount($attribute, $params)
    {
        $expense = $this->_expense;
        if ((float)$expense->requested_amount < (float)$this->amount) {
            $maxAmount = $expense->requested_amount;
            $this->addError($attribute, "Amount must be less or equal than {$maxAmount}.");
        }
    }

    // You can define attribute labels to make error messages more friendly
    public function attributeLabels()
    {
        return [
            'status' => 'Status',
            'amount' => 'Amount',
            'reason' => 'Reason',
        ];
    }

    public function store()
    {
        $model = new ExpenseApprovalProcesses();
        $model->admin_id = Yii::$app->user->id;
        $model->user_id =  $this->_expense->user_id;
        $model->admin_parent_id = Yii::$app->user->identity->parent_id;
        $model->expense_id = $this->_expense->id;
        $model->status_id = $this->status;
        $model->approved_amount = $this->status == 3 ? $this->amount: $this->_expense->requested_amount;
        $model->approved_reason = $this->reason;
        if ($model->save()) {
            $expenseModel = Expenses::findOne($this->_expense->id);
            $expenseModel->approved_amount = $model->approved_amount;
            $expenseModel->status_id = $this->status;
            $expenseModel->admin_id = Yii::$app->user->identity->parent_id;
            if ($expenseModel->save()) {
                return true;
            } else {
                print_r($expenseModel->getErrors());
                exit;
            }
        } 

        return false;
    }
}
