<?php

use yii\db\Migration;

class m250328_091411_insert_leave_auth_item_in_auth_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_items}}', [
            'id' => 75,
            'module_id' => 23,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 73

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 75,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250328_091411_insert_leave_auth_item_in_auth_items_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250328_091411_insert_leave_auth_item_in_auth_items_table cannot be reverted.\n";

        return false;
    }
    */
}
