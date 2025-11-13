<?php

use yii\db\Migration;

/**
 * Class m250107_050052_make_admin_nullable_expense_table
 */
class m250107_050052_make_admin_nullable_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('expenses', 'admin_id', $this->integer()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->alterColumn('expenses', 'admin_id', $this->integer()->notNull());

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250107_050052_make_admin_nullable_expense_table cannot be reverted.\n";

        return false;
    }
    */
}
