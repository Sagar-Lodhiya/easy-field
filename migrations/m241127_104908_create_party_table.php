<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%party}}`.
 */
class m241127_104908_create_party_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%parties}}', [
           'id' => $this->primaryKey(),
            'employee_id' => $this->integer(11)->notNull(),
            'party_category_id' => $this->integer(11),
            'dealer_name' => $this->string(150)->defaultValue(null),
            'dealer_phone' => $this->string(150)->defaultValue(null),
            'firm_name' => $this->string(150)->defaultValue(null),
            'address' => $this->text()->defaultValue(null),
            'city_or_town' => $this->string(150)->defaultValue(null),
            'gst_number' => $this->string(150)->defaultValue(null),
            'dealer_aadhar' => $this->string(150)->defaultValue(null),
            'is_active' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'is_deleted' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // Add foreign key for employee_id referencing the employees table
        $this->addForeignKey(
            'fk-parties-employee_id',
            '{{%parties}}',
            'employee_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Add foreign key for party_category_id referencing the party_categories table
        $this->addForeignKey(
            'fk-parties-party_category_id',
            '{{%parties}}',
            'party_category_id',
            '{{%party_categories}}', // Assuming there's a 'party_categories' table with the 'id' column
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%parties}}');
    }
}
