<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense_category}}`.
 */
class m241128_123137_create_expense_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->null(),
            'type' => "ENUM('limited_by_price', 'no_price_limit') NOT NULL COMMENT 'Type of the record'",
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expense_categories}}');
    }
}
