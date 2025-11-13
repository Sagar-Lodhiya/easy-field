<?php

use yii\db\Migration;

/**
 * Class m250110_044915_insert_auth_items_items_table
 */
class m250110_044915_insert_auth_items_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%auth_items}}', [
            'id' => 1,
            'module_id' => 1,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 1

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 2,
            'module_id' => 1,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 2

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 3,
            'module_id' => 1,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 3

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 4,
            'module_id' => 1,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 4

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 5,
            'module_id' => 1,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 5

        ]);



        $this->insert('{{%auth_items}}', [
            'id' => 6,
            'module_id' => 2,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 6

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 7,
            'module_id' => 2,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 7

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 8,
            'module_id' => 2,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 8

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 9,
            'module_id' => 2,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 9

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 10,
            'module_id' => 2,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 10

        ]);





        $this->insert('{{%auth_items}}', [
            'id' => 11,
            'module_id' => 4,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 11

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 12,
            'module_id' => 4,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 12

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 13,
            'module_id' => 4,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 13

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 14,
            'module_id' => 4,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 14

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 15,
            'module_id' => 4,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 15

        ]);





        $this->insert('{{%auth_items}}', [
            'id' => 16,
            'module_id' => 5,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 16

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 17,
            'module_id' => 5,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 17

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 18,
            'module_id' => 5,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 18

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 19,
            'module_id' => 5,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 19

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 20,
            'module_id' => 5,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 20

        ]);
       
       
        $this->insert('{{%auth_items}}', [
            'id' => 21,
            'module_id' => 5,
            'name' => 'Export',
            'url' => '/export',
            'sort_order' => 21

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 22,
            'module_id' => 5,
            'name' => 'Upload',
            'url' => '/upload',
            'sort_order' => 22

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 23,
            'module_id' => 7,
            'name' => 'Active User',
            'url' => '/active-user',
            'sort_order' => 23

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 24,
            'module_id' => 7,
            'name' => 'Absent User',
            'url' => '/absent-user',
            'sort_order' => 24

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 25,
            'module_id' => 7,
            'name' => 'Report',
            'url' => '/report',
            'sort_order' => 25

        ]);

        $this->insert('{{%auth_items}}', [
            'id' => 26,
            'module_id' => 7,
            'name' => 'Export',
            'url' => '/export',
            'sort_order' => 26

        ]);


        $this->insert('{{%auth_items}}', [
            'id' => 27,
            'module_id' => 8,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 27

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 28,
            'module_id' => 8,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 28

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 29,
            'module_id' => 8,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 29

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 30,
            'module_id' => 8,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 30

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 31,
            'module_id' => 8,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 31

        ]);

        $this->insert('{{%auth_items}}', [
            'id' => 32,
            'module_id' => 10,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 32

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 33,
            'module_id' => 10,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 33

        ]);

        $this->insert('{{%auth_items}}', [
            'id' => 34,
            'module_id' => 11,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 34

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 35,
            'module_id' => 11,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 35

        ]);


        $this->insert('{{%auth_items}}', [
            'id' => 36,
            'module_id' => 12,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 36

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 37,
            'module_id' => 12,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 37

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 38,
            'module_id' => 12,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 38

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 39,
            'module_id' => 12,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 39

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 40,
            'module_id' => 12,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 40

        ]);



        $this->insert('{{%auth_items}}', [
            'id' => 41,
            'module_id' => 13,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 41

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 42,
            'module_id' => 13,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 42

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 43,
            'module_id' => 13,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 43

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 44,
            'module_id' => 13,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 44

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 45,
            'module_id' => 13,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 45

        ]);











        $this->insert('{{%auth_items}}', [
            'id' => 46,
            'module_id' => 16,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 46

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 47,
            'module_id' => 16,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 47

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 48,
            'module_id' => 16,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 48

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 49,
            'module_id' => 16,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 49

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 50,
            'module_id' => 16,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 50

        ]);








        $this->insert('{{%auth_items}}', [
            'id' => 51,
            'module_id' => 17,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 51

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 52,
            'module_id' => 17,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 52

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 53,
            'module_id' => 17,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 53

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 54,
            'module_id' => 17,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 54

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 55,
            'module_id' => 17,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 55

        ]);







        $this->insert('{{%auth_items}}', [
            'id' => 56,
            'module_id' => 18,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 56

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 57,
            'module_id' => 18,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 57

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 58,
            'module_id' => 18,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 58

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 59,
            'module_id' => 18,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 59

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 60,
            'module_id' => 18,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 60

        ]);



        $this->insert('{{%auth_items}}', [
            'id' => 61,
            'module_id' => 20,
            'name' => 'List',
            'url' => '/index',
            'sort_order' => 61

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 62,
            'module_id' => 20,
            'name' => 'Create',
            'url' => '/create',
            'sort_order' => 62

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 63,
            'module_id' => 20,
            'name' => 'Update',
            'url' => '/update',
            'sort_order' => 63

        ]);
        
        $this->insert('{{%auth_items}}', [
            'id' => 64,
            'module_id' => 20,
            'name' => 'View',
            'url' => '/view',
            'sort_order' => 64

        ]);
       
        $this->insert('{{%auth_items}}', [
            'id' => 65,
            'module_id' => 20,
            'name' => 'Delete',
            'url' => '/delete',
            'sort_order' => 65

        ]);

        // Insert 'Super Admin' role in auth_roles
    $this->insert('{{%auth_roles}}', [
        'id' => 1,
        'name' => 'Super Admin',
        'is_active' => 1,
        'is_deleted' => 0,
    ]);

    // Insert auth_items_id 1 to 65 for role_id 1
    for ($i = 1; $i <= 65; $i++) {
        $this->insert('{{%auth_permissions}}', [
            'role_id' => 1,
            'auth_items_id' => $i,
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
    }
       
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250110_044915_insert_auth_items_items_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250110_044915_insert_auth_items_items_table cannot be reverted.\n";

        return false;
    }
    */
}
