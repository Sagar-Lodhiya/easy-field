<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expense_approval_processes".
 *
 * @property int $id
 * @property int $expense_id
 * @property int $user_id
 * @property float $approved_amount
 * @property string|null $approved_reason
 * @property int $admin_id
 * @property int $admin_parent_id
 * @property int $status_id
 * @property string $created_at
 *
 * @property Admins $admin
 * @property Admins $adminParent
 * @property Expenses $expense
 * @property Users $user
 * @property ExpenseStatuses $expenseStatus
 */
class ExpenseApprovalProcesses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense_approval_processes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expense_id', 'user_id', 'approved_amount', 'admin_id', 'status_id'], 'required'],
            [['expense_id', 'user_id', 'admin_id', 'admin_parent_id', 'status_id'], 'integer'],
            [['approved_amount'], 'number'],
            [['created_at'], 'safe'],
            [['approved_reason'], 'string', 'max' => 255],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['admin_id' => 'id']],
            [['admin_parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['admin_parent_id' => 'id']],
            [['expense_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expenses::class, 'targetAttribute' => ['expense_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseStatuses::class, 'targetAttribute' => ['status_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expense_id' => 'Expense ID',
            'user_id' => 'User ID',
            'expense_status_id' => 'Expence Status ID',
            'approved_amount' => 'Approved Amount',
            'approved_reason' => 'Approved Reason',
            'admin_id' => 'Admin ID',
            'admin_parent_id' => 'Admin Parent ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admins::class, ['id' => 'admin_id']);
    }

    /**
     * Gets query for [[AdminParent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdminParent()
    {
        return $this->hasOne(Admins::class, ['id' => 'admin_parent_id']);
    }

    /**
     * Gets query for [[Expense]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpense()
    {
        return $this->hasOne(Expenses::class, ['id' => 'expense_id']);
    }

    /**
     * Gets query for [[ExpenseStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseStatus()
    {
        return $this->hasOne(ExpenseStatuses::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
