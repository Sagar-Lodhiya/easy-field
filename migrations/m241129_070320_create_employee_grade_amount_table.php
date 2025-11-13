<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_grade_amount}}`.
 */
class m241129_070320_create_employee_grade_amount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_grades_amount}}', [
            'id' => $this->primaryKey(),
            'grade_id' => $this->integer()->notNull(),
            'city_grade' => $this->string(255)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee_grades_amount}}');
    }
}
