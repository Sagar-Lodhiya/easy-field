<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense_approval_processes}}`.
 */
class m250103_054317_create_expense_approval_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense_approval_processes}}', [
            'id' => $this->primaryKey(),
            'expense_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'approved_amount' => $this->float()->notNull(),
            'approved_reason' => $this->string()->defaultValue(null),
            'admin_id' => $this->integer()->notNull(),
            'admin_parent_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-expense_approval_processes-expense_id',
            '{{%expense_approval_processes}}',
            'expense_id',
            '{{%expenses}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expense_approval_processes-user_id',
            '{{%expense_approval_processes}}',
            'user_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-expense_approval_processes-admin_id',
            '{{%expense_approval_processes}}',
            'admin_id',
            '{{%admins}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-expense_approval_processes-admin_parent_id',
            '{{%expense_approval_processes}}',
            'admin_parent_id',
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
            'fk-expense_approval_processes-admin_parent_id',
            '{{%expense_approval_processes}}'
        );

        $this->dropForeignKey(
            'fk-expense_approval_processes-admin_id',
            '{{%expense_approval_processes}}'
        );

        $this->dropForeignKey(
            'fk-expense_approval_processes-user_id',
            '{{%expense_approval_processes}}'
        );

        $this->dropForeignKey(
            'fk-expense_approval_processes-expense_id',
            '{{%expense_approval_processes}}'
        );

        $this->dropTable('{{%expense_approval_processes}}');
    }
}
