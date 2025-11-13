<?php

use yii\db\Migration;

/**
 * Class m250116_155750_make_city_id_nullable_in_expense_table
 */
class m250116_155750_make_city_id_nullable_in_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('expenses', 'city_id', $this->integer()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250116_155750_make_city_id_nullable_in_expense_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250116_155750_make_city_id_nullable_in_expense_table cannot be reverted.\n";

        return false;
    }
    */
}
