<?php

use yii\db\Migration;

class m250327_052345_insert_fixed_and_claimed_expanse_in_auth_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->insert('{{%auth_items}}', [
            'id' => 70,
            'module_id' => 14,
            'name' => 'Index',
            'url' => '/index',
            'sort_order' => 70

        ]);
        $this->insert('{{%auth_items}}', [
            'id' => 71,
            'module_id' => 15,
            'name' => 'Index',
            'url' => '/index',
            'sort_order' => 71

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 70,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 71,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250327_052345_insert_fixed_and_claimed_expanse_in_auth_modules_table cannot be reverted.\n";

        return false;
    }
}
