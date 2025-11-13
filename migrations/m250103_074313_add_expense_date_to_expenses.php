<?php

use yii\db\Migration;

/**
 * Class m250103_074313_add_expense_date_to_expenses
 */
class m250103_074313_add_expense_date_to_expenses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('expenses', 'expense_date', $this->date()->notNull()->defaultValue(null));
  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('expenses', 'expense_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250103_074313_add_expense_date_to_expenses cannot be reverted.\n";

        return false;
    }
    */
}
