<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%expense_status}}`.
 */
class m250103_050839_create_expense_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%expense_statuses}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], 'ENGINE=InnoDB');

        $this->insert('{{%expense_statuses}}', [
            'name' => 'Requested'
        ]);

        $this->insert('{{%expense_statuses}}', [
            'name' => 'Pending'
        ]);
        
        $this->insert('{{%expense_statuses}}', [
            'name' => 'Partially Approved'
        ]);
        
        $this->insert('{{%expense_statuses}}', [
            'name' => 'Approved'
        ]);
        
        $this->insert('{{%expense_statuses}}', [
            'name' => 'Rejected'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%expense_status}}');
    }
}
