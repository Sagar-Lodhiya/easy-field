<?php

use yii\db\Migration;

/**
 * Class m250106_150912_add_status_id_to_expense_approval_processes_table
 */
class m250106_150912_add_status_id_to_expense_approval_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expense_approval_processes', 'status_id', $this->integer()->notNull());

         // Add the foreign key
         $this->addForeignKey(
            'fk-expense_approval_processes-employee_status_id', // Foreign key name
            '{{%expense_approval_processes}}',         // Table where the column is added
            'status_id',                // Column in the child table
            '{{%expense_statuses}}',        // Referenced table
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
            'fk-expense_approval_processes-employee_status_id',
            '{{%expense_approval_processes}}'
        );


        $this->dropColumn('{{%expense_approval_processes}}', 'status_id');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250106_150912_add_status_id_to_expense_approval_processes_table cannot be reverted.\n";

        return false;
    }
    */
}
