<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leave_type}}`.
 */
class m250109_094222_create_leave_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%leave_type}}', [
            'id' => $this->primaryKey(),
            'leave_type' => $this->string()->notNull(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%leave_type}}');
    }
}
