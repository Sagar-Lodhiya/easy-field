<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expenses}}`.
 */
class m250103_051138_create_expenses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%expenses}}', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'user_id'=> $this->integer()->notNull(),
            'category_id'=> $this->integer()->notNull(),
            'requested_amount' => $this->float()->notNull(),
            'approved_amount' => $this->float()->notNull(),
            'remark' => $this->string()->defaultValue(null),
            'is_night_stay' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'expense_photo' => $this->string()->notNull(),
            'expense_details' => $this->string()->defaultValue(null),
            'admin_id' => $this->integer()->notNull(),
            'expense_type' => $this->string()->defaultValue('fixed'),
            'status_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-expenses-city_id',
            '{{%expenses}}',
            'city_id',
            '{{%city_grades}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expenses-user_id',
            '{{%expenses}}',
            'user_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expenses-category_id',
            '{{%expenses}}',
            'category_id',
            '{{%expense_categories}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
       
        $this->addForeignKey(
            'fk-expenses-admin_id',
            '{{%expenses}}',
            'admin_id',
            '{{%admins}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-expenses-status_id',
            '{{%expenses}}',
            'status_id',
            '{{%expense_statuses}}', // Assuming there's an 'employees' table with the 'id' column
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
            'fk-expenses-status_id',
            '{{%expenses}}'
        );

        $this->dropForeignKey(
            'fk-expenses-admin_id',
            '{{%expenses}}'
        );
        
        $this->dropForeignKey(
            'fk-expenses-category_id',
            '{{%expenses}}'
        );

        $this->dropForeignKey(
            'fk-expenses-user_id',
            '{{%expenses}}'
        );

        $this->dropForeignKey(
            'fk-expenses-city_id',
            '{{%expenses}}'
        );

        $this->dropTable('{{%expenses}}');
    }
}
