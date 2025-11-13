<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class EmployeeGradeForm extends Model
{
    public $grades = [];
    public $name;
    public $id;
    public $categories = [];

    /**
     * Initialize default values for grades
     */
    public function init($id = null)
    {
        parent::init();

        // Default grades with empty categories
        $this->grades = [
            ['type' => 'A', 'type_id' => 1, 'categories' => []],
            ['type' => 'B', 'type_id' => 2, 'categories' => []],
            ['type' => 'C', 'type_id' => 3, 'categories' => []],
            ['type' => 'D', 'type_id' => 4, 'categories' => []],
        ];

        $this->categories = ExpenseCategories::find()->where(['is_active' => 1, 'is_deleted' => 0, 'type' => 'limited_by_price'])->all();
    }

    // Define the validation rules
    public function rules()
    {
        return [
            // Validate grades array
            [
                ['grades', 'name'],
                'required'
            ],
            // [
            //     ['grades'],
            //     'each',
            //     'rule' => [
            //         'array', // Ensure each grade is an array with 'type' and 'categories'
            //         ['type', 'string'], // Type should be a string (A, B, etc.)
            //         [
            //             'categories',
            //             'each',
            //             'rule' => [
            //                 'array', // Each category should be an array with 'category_id' and 'amount'
            //                 ['amount', 'required'], // Amount should be required
            //                 ['amount', 'number', 'min' => 0], // Amount should be numeric and non-negative
            //             ]
            //         ]
            //     ]
            // ],
        ];
    }

    // You can define attribute labels to make error messages more friendly
    public function attributeLabels()
    {
        return [
            'grades' => 'Amount',
            'name' => 'Name',
        ];
    }

    public function loadData($id)
    {
        $employeeGrade = EmployeeGrades::findOne($id);
    
        if (!$employeeGrade) {
            throw new \Exception("Employee grade not found");
        }
    
        $this->name = $employeeGrade->name;
    
        // Fetch all grades
        $gradesModel = Grades::find()->all();
        $gradeArray = [];
    
        foreach ($gradesModel as $grade) {
            // Filter amounts for the current grade
            $filteredAmounts = array_filter(
                $employeeGrade->employeeGradesAmounts,
                fn($item) => $item->city_grade_id == $grade->id
            );
    
            // Map filtered amounts to structured categories and reindex
            $categories = array_values(
                array_map(
                    fn($item) => [
                        'category_id' => $item->category_id,
                        'category_name' => $item->expenseCategory->name,
                        'amount' => $item->amount,
                    ],
                    $filteredAmounts
                )
            );
    
            // Build the grade array
            $gradeArray[] = [
                'type' => $grade->name,
                'type_id' => $grade->id,
                'categories' => $categories,
            ];
        }
    
        $this->grades = $gradeArray;
    }
    
    
}
