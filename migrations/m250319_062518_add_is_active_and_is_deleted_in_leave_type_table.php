<?php

use yii\db\Migration;

/**
 * Class m250319_062518_add_is_active_and_is_deleted_in_leave_type_table
 */
class m250319_062518_add_is_active_and_is_deleted_in_leave_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%leave_type}}', 'is_active', $this->tinyInteger(1)->defaultValue(1));
        $this->addColumn('{{%leave_type}}', 'is_deleted', $this->tinyInteger(1)->defaultValue(0));
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250319_062518_add_is_active_and_is_deleted_in_leave_type_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250319_062518_add_is_active_and_is_deleted_in_leave_type_table cannot be reverted.\n";

        return false;
    }
    */
}
