<?php

use yii\db\Migration;

class m250327_065101_insert_leave_in_auth_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_modules}}', [
            'id' => 23,
            'name' => 'Leave  ',
            'url' => '/leave',
            'sort_order' => 23

        ]);

        $this->insert('{{%auth_items}}', [
            'id' => 74,
            'module_id' => 23,
            'name' => 'Index',
            'url' => '/index',
            'sort_order' => 73

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 74,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250327_065101_insert_leave_in_auth_module_table cannot be reverted.\n";

        return false;
    }
}
