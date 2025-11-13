<?php

use yii\db\Migration;

/**
 * Class m250110_043529_insert_auth_modules_items_table
 */
class m250110_043529_insert_auth_modules_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_modules}}', [
            'id' => 1,
            'name' => 'Admin',
            'url' => '/admins',
            'sort_order' => 1

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 2,
            'name' => 'Users',
            'url' => '/users',
            'sort_order' => 2

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 3,
            'name' => 'Tree View',
            'url' => '/tree',
            'sort_order' => 3

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 4,
            'name' => 'Party Categories',
            'url' => '/party-categories',
            'sort_order' => 4

        ]);
       
        $this->insert('{{%auth_modules}}', [
            'id' => 5,
            'name' => 'Parties',
            'url' => '/parties',
            'sort_order' => 5

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 6,
            'name' => 'Live Map',
            'url' => '/map',
            'sort_order' => 6

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 7,
            'name' => 'Attendance',
            'url' => '/attendance',
            'sort_order' => 7

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 8,
            'name' => 'Visits',
            'url' => '/visits',
            'sort_order' => 8

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 10,
            'name' => 'Payments',
            'url' => '/payments',
            'sort_order' => 10

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 11,
            'name' => 'Notifications',
            'url' => '/notifications',
            'sort_order' => 11

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 12,
            'name' => 'Off Days',
            'url' => '/off-days',
            'sort_order' => 12

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 13,
            'name' => 'Expense Categories',
            'url' => '/expense-categories',
            'sort_order' => 13

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 14,
            'name' => 'Fixed Expense',
            'url' => '/fixed-expenses',
            'sort_order' => 14

        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 15,
            'name' => 'Claimed Expense',
            'url' => '/claimed-expenses',
            'sort_order' => 15
        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 16,
            'name' => 'City Grades',
            'url' => '/city-grades',
            'sort_order' => 16
        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 17,
            'name' => 'Employee Grades',
            'url' => '/employee-grades',
            'sort_order' => 17
        ]);
        
        $this->insert('{{%auth_modules}}', [
            'id' => 18,
            'name' => 'States',
            'url' => '/states',
            'sort_order' => 18
        ]);
       
        $this->insert('{{%auth_modules}}', [
            'id' => 19,
            'name' => 'Settings',
            'url' => '/setting',
            'sort_order' => 19
        ]);
       
        $this->insert('{{%auth_modules}}', [
            'id' => 20,
            'name' => 'Leave Type',
            'url' => '/leave-type',
            'sort_order' => 20
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250110_043529_insert_auth_modules_items_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250110_043529_insert_auth_modules_items_table cannot be reverted.\n";

        return false;
    }
    */
}
