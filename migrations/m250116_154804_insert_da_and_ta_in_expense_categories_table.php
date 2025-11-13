<?php

use yii\db\Migration;

/**
 * Class m250116_154804_insert_da_and_ta_in_expense_categories_table
 */
class m250116_154804_insert_da_and_ta_in_expense_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('expense_categories', [
            'id' => 1,
            'name' => 'Daily Allowance',
            'type' => 'no_price_limit',
        ]);
    
        $this->insert('expense_categories', [
            'id' => 2,
            'name' => 'Travelling Allowance',
            'type' => 'no_price_limit',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250116_154804_insert_da_and_ta_in_expense_categories_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250116_154804_insert_da_and_ta_in_expense_categories_table cannot be reverted.\n";

        return false;
    }
    */
}
