<?php

use yii\db\Migration;

class m250327_042258_insert_map_module_in_auth_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_modules}}', [
            'id' => 21,
            'name' => 'Map',
            'url' => '/map',
            'sort_order' => 21
        ]);

        $this->insert('{{%auth_items}}', [
            'id' => 69,
            'module_id' => 21,
            'name' => 'Index',
            'url' => '/index',
            'sort_order' => 69

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 69,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250327_042258_insert_map_module_in_auth_modules_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250327_042258_insert_map_module_in_auth_modules_table cannot be reverted.\n";

        return false;
    }
    */
}
