<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%settings}}`.
 */
class m250102_102425_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->insert('{{%settings}}', [
            'type' => 'employee_add_limit',
            'value' => '100'
        ]);
        
        $this->insert('{{%settings}}', [
            'type' => 'sub_admin_add_limit',
            'value' => '100'
        ]);
        
        $this->insert('{{%settings}}', [
            'type' => 'traveling_rate_car',
            'value' => '50'
        ]);
        
        $this->insert('{{%settings}}', [
            'type' => 'traveling_rate_bike',
            'value' => '30'
        ]);
       
        $this->insert('{{%settings}}', [
            'type' => 'ping_interval',
            'value' => '5'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
