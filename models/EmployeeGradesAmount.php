<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_grades_amount".
 *
 * @property int $id
 * @property int $grade_id
 * @property int $city_grade_id
 * @property int $category_id
 * @property float $amount
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ExpenseCategories $expenseCategory
 * @property Grades $cityGrade
 * @property Grades $grade
 */
class EmployeeGradesAmount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_grades_amount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grade_id', 'city_grade_id', 'category_id', 'amount'], 'required'],
            [['grade_id', 'city_grade_id', 'category_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpenseCategories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grades::class, 'targetAttribute' => ['city_grade_id' => 'id']],
            [['grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grades::class, 'targetAttribute' => ['grade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'grade_id' => 'Grade ID',
            'city_grade_id' => 'City Grade ID',
            'category_id' => 'Category ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseCategory()
    {
        return $this->hasOne(ExpenseCategories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[CityGrade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityGrade()
    {
        return $this->hasOne(Grades::class, ['id' => 'city_grade_id']);
    }

    /**
     * Gets query for [[Grade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrade()
    {
        return $this->hasOne(Grades::class, ['id' => 'grade_id']);
    }
}
