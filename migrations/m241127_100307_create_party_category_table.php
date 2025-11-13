<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%party_category}}`.
 */
class m241127_100307_create_party_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%party_categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%party_categories}}');
    }
}
