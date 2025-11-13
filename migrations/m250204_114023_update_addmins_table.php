<?php

use yii\db\Migration;

/**
 * Class m250204_114023_update_addmins_table
 */
class m250204_114023_update_addmins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%admins}}', 'role_id', $this->integer()->notNull()->defaultValue(1));
        
        $this->addForeignKey(
            'fk-admins_roles-role_id', // FK name
            'admins',                 // Table with FK
            'role_id',                          // FK column
            'auth_roles',                                 // Referenced table
            'id',                                     // Referenced column
            'CASCADE',                                // On delete
            'CASCADE'                                 // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%admins}}', 'role_id', $this->integer()->notNull());
        
        $this->addForeignKey(
            'fk-admins_roles-role_id', // FK name
            'auth_roles',                 // Table with FK
            'id',                          // FK column
            'admins',                                 // Referenced table
            'role_id',                                     // Referenced column
            'CASCADE',                                // On delete
            'CASCADE'                                 // On update
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241228_052752_add_is_active_in_states_table_and_foreign_key_to_city_grade_table cannot be reverted.\n";

        return false;
    }
    */
}
