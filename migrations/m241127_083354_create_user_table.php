<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m241127_083354_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'last_name' => $this->string(100)->notNull(),
            'employee_id' => $this->string(100)->notNull(),
            'email' => $this->string(150)->notNull(),
            'phone_no' => $this->string(150)->notNull(),
            'profile' => $this->string(150)->notNull(),
            'parent_id' => $this->integer(),
            'address' => $this->string(255)->defaultValue(null),
            'da_amount' => $this->string(255)->defaultValue(null),
            'da_with_night_stay_amount' => $this->string(255)->defaultValue(null),
            'eligible_km' => $this->string(255)->defaultValue(null),
            'device_id' => $this->string(255)->defaultValue(null),
            'device_type' => $this->string(50)->defaultValue(null),
            'device_model' => $this->string(100)->defaultValue(null),
            'app_version' => $this->string(50)->defaultValue(null),
            'os_version' => $this->string(50)->defaultValue(null),
            'device_token' => $this->string(255)->defaultValue(null),
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // Add foreign key for parent_id referencing the admins table
        $this->addForeignKey(
            'fk-users-parent_id', // Foreign key name
            '{{%users}}', // Current table
            'parent_id', // Column in the current table
            '{{%admins}}', // Referenced table
            'id', // Referenced column
            'SET NULL', // On delete
            'CASCADE' // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
