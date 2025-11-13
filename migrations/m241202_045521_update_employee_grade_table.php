<?php

use yii\db\Migration;

/**
 * Class m241202_045521_update_employee_grade_table
 */
class m241202_045521_update_employee_grade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Step 1: Rename the column to city_grade_id
        $this->renameColumn('employee_grades_amount', 'city_grade', 'city_grade_id');

        // Step 2: Change the datatype to integer
        $this->alterColumn('employee_grades_amount', 'city_grade_id', $this->integer()->notNull());

        // Step 3: Add a foreign key constraint
        $this->addForeignKey(
            'fk-employee_grades_amount-city_grade_id', // FK name
            'employee_grades_amount',                 // Table with FK
            'city_grade_id',                          // FK column
            'grades',                                 // Referenced table
            'id',                                     // Referenced column
            'CASCADE',                                // On delete
            'CASCADE'                                 // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-employee_grades_amount-city_grade_id', 'employee_grades_amount');

        // Step 2: Change the column back to varchar
        $this->alterColumn('employee_grades_amount', 'city_grade_id', $this->string(255)->notNull());

        // Step 3: Rename the column back to city_grade
        $this->renameColumn('employee_grades_amount', 'city_grade_id', 'city_grade');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241202_045521_update_employee_grade_table cannot be reverted.\n";

        return false;
    }
    */
}
