<?php

use yii\db\Migration;

class m250326_125827_insert_reset_device_in_auth_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->insert('{{%auth_items}}', [
            'id' => 67,
            'module_id' => 2,
            'name' => 'Reset Device',
            'url' => '/reset-device',
            'sort_order' => 67

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 67,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250326_125827_insert_reset_device_in_auth_items_table cannot be reverted.\n";

        return false;
    }

  
}
