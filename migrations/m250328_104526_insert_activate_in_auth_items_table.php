<?php

use yii\db\Migration;

class m250328_104526_insert_activate_in_auth_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $auth_modules = [1, 2, 4, 5, 13, 12, 16, 17, 18];
        $name = 'Activate';
        $url = '/activate';
        $sort_order = 76;

        foreach ($auth_modules as $auth_module) {
            $this->insert('{{%auth_items}}', [
                'id' => $sort_order,
                'module_id' => $auth_module,
                'name' => $name,
                'url' => $url,
                'sort_order' => $sort_order

            ]);

            $this->insert('{{%auth_permissions}}', [
                'role_id' => 1,
                'auth_items_id' => $sort_order,
                'is_active' => 1,
                'is_deleted' => 0,
            ]);
            $sort_order += 1;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250328_104526_insert_activate_in_auth_items_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250328_104526_insert_activate_in_auth_items_table cannot be reverted.\n";

        return false;
    }
    */
}
