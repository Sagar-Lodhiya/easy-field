<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%off_day}}`.
 */
class m241128_132305_create_off_day_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%off_days}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'type' => $this->integer(11)->notNull()->defaultValue(1)->comment('The type of off day: 1 = Weekly, 2 = Monthly, 3 = Yearly, 4 = Once-off'),
            'date' => $this->date()->notNull(),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%off_days}}');
    }
}
