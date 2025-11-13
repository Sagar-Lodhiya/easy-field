<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_modules}}`.
 */
class m250110_042151_create_auth_modules_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_modules}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'url' => $this->string(100)->notNull(),
            'sort_order' => $this->integer(11)->notNull(),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB');

        $this->createTable('{{%auth_items}}', [
            'id' => $this->primaryKey(),
            'module_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'url' => $this->string(100)->notNull(),
            'sort_order' => $this->integer(11)->notNull(),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-auth_items-module_id',
            '{{%auth_items}}',
            'module_id',
            '{{%auth_modules}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('{{%auth_roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB');

        $this->createTable('{{%auth_permissions}}', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull(),
            'auth_items_id' => $this->integer()->notNull(),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-auth_permissions-role_id',
            '{{%auth_permissions}}',
            'role_id',
            '{{%auth_roles}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-auth_permissions-auth_items_id',
            '{{%auth_permissions}}',
            'auth_items_id',
            '{{%auth_items}}', // Assuming there's an 'employees' table with the 'id' column
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
            'fk-auth_permissions-auth_items_id',
            '{{%auth_permissions}}',
        );

        $this->dropForeignKey(
            'fk-auth_permissions-role_id',
            '{{%auth_permissions}}',
        );

        $this->dropTable('{{%auth_permissions}}');

        $this->dropTable('{{%auth_roles}}');

        $this->dropForeignKey(
            'fk-auth_items-module_id',
            '{{%auth_items}}',
        );

        $this->dropTable('{{%auth_items}}');

        $this->dropTable('{{%auth_modules}}');
    }
}
