<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%leave}}`.
 */
class m250109_100109_create_leave_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%leave}}', [
            'id' => $this->primaryKey(),  // id as the primary key
            'leave_type_id' => $this->integer()->notNull(),  // leave_type as foreign key
            'user_id' => $this->integer()->notNull(),  // user_id as foreign key
            'start_date' => $this->date()->notNull(),  // start_date field (date type)
            'end_date' => $this->date()->notNull(),  // end_date field (date type)
            'reason' => $this->text()->notNull(),  // reason field (text type)
            'created_at' => $this->integer()->notNull(),  // created_at field (timestamp)
            'updated_at' => $this->integer()->notNull(),  // updated_at field (timestamp)
            'status' => $this->smallInteger()->notNull()->defaultValue(1),

        ], 'ENGINE=InnoDB');
        // Add foreign key for leave_type_id
        $this->addForeignKey(
            'fk_leave_leave_type',  // name of the foreign key constraint
            'leave',  // the table to which the foreign key is added
            'leave_type_id',  // the column that references the foreign key
            'leave_type',  // the referenced table
            'id',  // the column in the referenced table
            'CASCADE'  // what happens when the referenced row is deleted
        );

        // Add foreign key for user_id
        $this->addForeignKey(
            'fk_leave_user',  // name of the foreign key constraint
            'leave',  // the table to which the foreign key is added
            'user_id',  // the column that references the foreign key
            'users',  // the referenced table
            'id',  // the column in the referenced table
            'CASCADE'  // what happens when the referenced row is deleted
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_leave_leave_type', 'leave');
        $this->dropForeignKey('fk_leave_user', 'leave');
        $this->dropTable('{{%leave}}');
    }
}
