<?php

use yii\db\Migration;

/**
 * Class m250319_105836_insert_attendance_details_in_auth_items_table
 */
class m250319_105836_insert_attendance_details_in_auth_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->insert('{{%auth_items}}', [
            'id' => 66,
            'module_id' => 7,
            'name' => 'Detail',
            'url' => '/detail',
            'sort_order' => 66

        ]);

        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => 66,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
   
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250319_105836_insert_attendance_details_in_auth_items_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250319_105836_insert_attendance_details_in_auth_items_table cannot be reverted.\n";

        return false;
    }
    */
}
