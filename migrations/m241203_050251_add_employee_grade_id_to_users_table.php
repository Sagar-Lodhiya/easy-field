<?php

use yii\db\Migration;

/**
 * Class m241203_050251_add_employee_grade_id_to_users_table
 */
class m241203_050251_add_employee_grade_id_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add the new column
        $this->addColumn('{{%users}}', 'employee_grade_id', $this->integer()->notNull());

        // Add the foreign key
        $this->addForeignKey(
            'fk-users-employee_grade_id', // Foreign key name
            '{{%users}}',         // Table where the column is added
            'employee_grade_id',                // Column in the child table
            '{{%employee_grades}}',        // Referenced table
            'id',                       // Referenced column
            'CASCADE',                  // On delete
            'CASCADE'                   // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign key
        $this->dropForeignKey(
            'fk-users-employee_grade_id',
            '{{%users}}'
        );

        // Drop the column
        $this->dropColumn('{{%users}}', 'employee_grade_id');
    }

}
