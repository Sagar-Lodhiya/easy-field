<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%visits}}`.
 */
class m250106_072901_create_visits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%visits}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'party_id' => $this->integer()->notNull(),
            'duration' => $this->string(50)->defaultValue(null),
            'discussion_point' => $this->string(250)->defaultValue(null),
            'remark' => $this->string(250)->defaultValue(null),
            'place' => $this->string(250)->defaultValue(null),
            'time' =>$this->time()->null(),
            'latitude' => $this->float()->defaultValue(null),
            'longitude' => $this->float()->defaultValue(null),
            'is_deleted' => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-visits-party_id',
            '{{%visits}}',
            'party_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-visits-user_id',
            '{{%visits}}',
            'user_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
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
            'fk-visits-user_id',
            '{{%visits}}'
        );
        
        $this->dropForeignKey(
            'fk-visits-party_id',
            '{{%visits}}'
        );

        $this->dropTable('{{%visits}}');
    }
}
