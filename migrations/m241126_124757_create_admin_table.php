<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%admin}}`.
 */
class m241126_124757_create_admin_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%admins}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password' => $this->string(150)->notNull(),
            'password_reset_token' => $this->string(150)->unique(),
            'email' => $this->string(150)->notNull(),
            'parent_id' => $this->integer()->defaultValue(null), // Parent ID column
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ],'ENGINE=InnoDB');

        // Add foreign key constraint to the parent_id column
        $this->addForeignKey(
            'fk-admins-parent_id', // Foreign key name
            '{{%admins}}', // Current table
            'parent_id', // Column in the current table
            '{{%admins}}', // Referenced table
            'id', // Referenced column
            'SET NULL', // On delete
            'CASCADE' // On update
        );

        // Insert a default user record
        $this->insert('{{%admins}}', [
            'name' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(), // Generates a random auth key
            'password' => Yii::$app->security->generatePasswordHash('123456'), // Hashes the password 'admin123'
            'email' => 'admin@example.com',
            'parent_id' => null, // Default null
            'is_active' => 1,
            'is_deleted' => 0,
            'created_at' => time(),
            'updated_at' => time(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraint first
        $this->dropForeignKey(
            'fk-admins-parent_id',
            '{{%admins}}'
        );

        // Drop the table
        $this->dropTable('{{%admins}}');
    }
}
