<?php

use yii\db\Migration;

class m250327_055254_insert_settings_and_cms_in_auth_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_items}}', [
            'id' => 72,
            'module_id' => 19,
            'name' => 'System',
            'url' => '/system',
            'sort_order' => 70

        ]);
        $this->insert('{{%auth_modules}}', [
            'id' => 22,
            'name' => 'CMS  ',
            'url' => '/cms',
            'sort_order' => 22

        ]);
        $this->insert('{{%auth_items}}', [
            'id' => 73,
            'module_id' => 22,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 73

        ]);
   

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 72,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 73,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250327_055254_insert_settings_and_cms_in_auth_modules_table cannot be reverted.\n";

        // Delete records from auth_permissions first to avoid foreign key constraint issues
        $this->delete('{{%auth_permissions}}', ['auth_items_id' => 73]);
        $this->delete('{{%auth_permissions}}', ['auth_items_id' => 72]);

        // Delete auth_items records
        $this->delete('{{%auth_items}}', ['id' => 74]);
        $this->delete('{{%auth_items}}', ['id' => 73]);
        $this->delete('{{%auth_items}}', ['id' => 72]);

        // Delete auth_modules record
        $this->delete('{{%auth_modules}}', ['id' => 22]);

        return true;
    }
}
