<?php

use yii\db\Migration;

/**
 * Class m250320_090411_add_foreign_key_to_employee_grade_amount
 */
class m250320_090411_add_foreign_key_to_employee_grade_amount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add foreign key for `grade_id` column
        $this->addForeignKey(
            'fk-employee_grades_amount-grade_id', // Foreign key name
            '{{%employee_grades_amount}}',       // Current table
            'grade_id',                          // Column in current table
            '{{%grades}}',                       // Referenced table
            'id',                                // Referenced column
            'CASCADE',                           // On delete
            'CASCADE'                            // On update
        );

        // Add foreign key for `category_id` column
        $this->addForeignKey(
            'fk-employee_grades_amount-category_id',
            '{{%employee_grades_amount}}',
            'category_id',
            '{{%expense_categories}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign keys first
        $this->dropForeignKey('fk-employee_grades_amount-grade_id', '{{%employee_grades_amount}}');
        $this->dropForeignKey('fk-employee_grades_amount-category_id', '{{%employee_grades_amount}}');
    }

}