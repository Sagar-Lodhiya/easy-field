<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property int $city_id
 * @property int $user_id
 * @property int $category_id
 * @property float $requested_amount
 * @property float $approved_amount
 * @property string|null $remark
 * @property int $is_night_stay
 * @property string $expense_photo
 * @property string|null $expense_details
 * @property int $admin_id
 * @property string|null $expense_type
 * @property string|null $expense_date
 * @property int $status_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Admins $admin
 * @property ExpenseCategories $category
 * @property CityGrades $city
 * @property ExpenseApprovalProcesses[] $expenseApprovalProcesses
 * @property ExpenseStatuses $status
 * @property Users $user
 */
class Expenses extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'requested_amount', 'expense_photo', 'status_id'], 'required'],
            [['city_id', 'user_id', 'category_id', 'is_night_stay', 'admin_id', 'status_id'], 'integer'],
            [['image'], 'file'],
            [['requested_amount', 'approved_amount'], 'number'],
            [['created_at', 'updated_at', 'expense_date'], 'safe'],
            [['remark', 'expense_photo', 'expense_details', 'expense_type'], 'string', 'max' => 255],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['admin_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CityGrades::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'city_id' => 'City',
            'user_id' => 'User',
            'category_id' => 'Category',
            'requested_amount' => 'Requested Amount',
            'approved_amount' => 'Approved Amount',
            'remark' => 'Remark',
            'is_night_stay' => 'Is Night Stay',
            'expense_photo' => 'Expense Photo',
            'expense_details' => 'Expense Details',
            'admin_id' => 'Admin',
            'expense_type' => 'Expense Type',
            'status_id' => 'Status',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ExpenseCategories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(CityGrades::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[ExpenseApprovalProcesses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseApprovalProcesses()
    {
        return $this->hasMany(ExpenseApprovalProcesses::class, ['expense_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
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


    public function getExpenseAmount()
    {
        return $this->requested_amount;
    }



    public static function getTotalRequestedAmountAndCount()
    {
        // Get total requested amount where approved_amount is NULL for all users
        $totalRequestedAmount = self::find()
            ->where(['approved_amount' => null])
            ->sum('requested_amount');

        // Get the count of expenses where approved_amount is NULL for all users
        $expenseCount = self::find()
            ->where(['approved_amount' => null])
            ->count();

        // Return the result as an object, so you can access properties
        return (object)[
            'totalRequestedAmount' => $totalRequestedAmount,
            'expenseCount' => $expenseCount
        ];
    }
}
