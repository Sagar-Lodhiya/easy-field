<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%office}}`.
 */
class m250829_052556_create_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%office}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'latitude' => $this->string()->notNull(),
            'longitude' => $this->string()->notNull(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'), 
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%office}}');
    }
}
