<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notifications}}`.
 */
class m250103_060219_create_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'text'  => $this->string()->notNull(),
            'admin_id'  => $this->integer()->notNull(),
            'is_read' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'is_cleared' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-notifications-user_id',
            '{{%notifications}}',
            'user_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
       
        $this->addForeignKey(
            'fk-notifications-admin_id',
            '{{%notifications}}',
            'admin_id',
            '{{%admins}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-notifications-admin_id',
            '{{%notifications}}'
        );

        $this->dropForeignKey(
            'fk-notifications-user_id',
            '{{%notifications}}'
        );
        
        $this->dropTable('{{%notifications}}');
    }
}









