<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%city_grade}}`.
 */
class m241128_130514_create_city_grade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%city_grades}}', [
            'id' => $this->primaryKey(),
            'city' => $this->string(150)->notNull(),
            'grade_id' => $this->Integer()->notNull(),
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
        $this->dropTable('{{%city_grades}}');
    }
}
