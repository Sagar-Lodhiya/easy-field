<?php

use yii\db\Migration;

class m250327_041054_insert_tree_index_in_auth_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_items}}', [
            'id' => 68,
            'module_id' => 3,
            'name' => 'Index',
            'url' => '/index',
            'sort_order' => 68

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 68,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250327_041054_insert_tree_index_in_auth_items_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250327_041054_insert_tree_index_in_auth_items_table cannot be reverted.\n";

        return false;
    }
    */
}
