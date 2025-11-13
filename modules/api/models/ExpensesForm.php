<?php

namespace app\modules\api\models;

use app\models\EmployeeGradesAmount;
use app\models\ExpenseCategories;
use app\models\Users;
use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\api\helpers\ImageUploadHelper;
use app\models\Expenses;
use Yii;

class ExpensesForm extends Model
{
    public $city_id;
    public $user_id; // Automatically set to logged-in user
    public $category_id;
    public $requested_amount;
    public $expense_photo;
    public $expense_details;
    public $expense_date;
    public $status_id;
    public $_message = '';

    public function rules()
    {
        return [
            [['city_id', 'category_id', 'requested_amount', 'expense_details', 'expense_date'], 'required'],
            [['city_id', 'user_id', 'status_id'], 'integer'],
            [['requested_amount'], 'number'],
            [['expense_details'], 'string', 'max' => 500],
            [['expense_date'], 'date', 'format' => 'php:Y-m-d'],
            [['expense_photo'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
            [['requested_amount'], 'validateAmount'],
        ];
    }

    public function validateAmount($attribute, $params)
    {
        $user_id = Yii::$app->user->identity->id;
        $employeeGrade = Users::findOne($user_id)->employeeGrade;

        $city_id = $this->city_id;
        $category_id = $this->category_id;

        $expenseCategory = ExpenseCategories::find()->where(['id' => $category_id])->one();
        $amount = EmployeeGradesAmount::find()->where(['grade_id' => $employeeGrade, 'city_grade_id' => $city_id, 'category_id' => $category_id])->one();
        if ($expenseCategory->type == 'limited_by_price') {
            if ($this->requested_amount > $amount->amount) {
                $this->addError($attribute, 'Requested amount cannot be gretaer than ' . $amount->amount);
            }
        }
        return true;
    }

    public function saveExpense()
    {
        if ($this->validate()) {
            $expense = new Expenses();
            $expense->attributes = $this->attributes;
            $expense->status_id = 1;


            // Set user_id and admin_id
            $expense->user_id = Yii::$app->user->id;  // Logged-in user
            $expense->admin_id = Yii::$app->user->identity->parent_id;  // Parent of the user
            $expense->expense_type = 'claimed';  // Parent of the 
            $expense->is_night_stay = 0;
            $categoryName = $expense->category ? $expense->category->name : null;
            $cityName = $expense->city ? $expense->city->city : null;


            $imageFile = UploadedFile::getInstanceByName('expense_photo');

            $validation = ImageUploadHelper::validateImage($imageFile);

            if (!$validation) {
                $this->addError('expense_photo', 'Invalid File image');
                return false;
            }

            $expense->expense_photo = ImageUploadHelper::uploadImage($imageFile);

            if ($expense->save()) {
                return [

                    'id' => $expense->id,
                    'expense_details' => $expense->expense_details,
                    'category_id' => $expense->category_id,
                    'category_name' => $categoryName,
                    'requested_amount' => $expense->requested_amount,
                    'city_id' => $expense->city_id,
                    'city_name' => $cityName,
                    'expense_photo' => $expense->expense_photo,
                    'expense_date' => $expense->expense_date,

                ];
            }




            return false;
        }
    }
    public static function listExpenses($userId)
    {
        return Expenses::find()->where(['user_id' => $userId])->all();
    }
}
