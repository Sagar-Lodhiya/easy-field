<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payments}}`.
 */
class m250107_073442_create_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
           'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'party_id' => $this->integer()->notNull(),
            'amount' => $this->string(50)->defaultValue(null),
            'amount_type' => $this->string(250)->defaultValue(null),
            'remark' => $this->string(250)->defaultValue(null),
            'collection_of' => $this->string(250)->defaultValue(null),
            'payments_details' => $this->string(250)->defaultValue(null),
            'amount_details' => $this->string(250)->defaultValue(null),
            'extra' => $this->string(250)->defaultValue(null),
            'payment_of' =>$this->string(250)->null(),
            'payments_photo' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'is_deleted' => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->addForeignKey(
            'fk-payments-party_id',
            '{{%payments}}',
            'party_id',
            '{{%parties}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-payments-user_id',
            '{{%payments}}',
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
            'fk-payments-user_id',
            '{{%payments}}'
        );
        
        $this->dropForeignKey(
            'fk-payments-party_id',
            '{{%payments}}'
        );

        $this->dropTable('{{%payments}}');
    }
}
