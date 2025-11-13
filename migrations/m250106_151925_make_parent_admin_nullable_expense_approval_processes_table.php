<?php

use yii\db\Migration;

/**
 * Class m250106_151925_make_parent_admin_nullable_expense_approval_processes_table
 */
class m250106_151925_make_parent_admin_nullable_expense_approval_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('expense_approval_processes', 'admin_parent_id', $this->integer()->null());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->alterColumn('expense_approval_processes', 'admin_parent_id', $this->integer()->notNull());
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250106_151925_make_parent_admin_nullable_expense_approval_processes_table cannot be reverted.\n";

        return false;
    }
    */
}
