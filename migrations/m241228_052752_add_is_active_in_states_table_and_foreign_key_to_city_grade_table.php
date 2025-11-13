<?php

use yii\db\Migration;

/**
 * Class m241228_052752_add_is_active_in_states_table_and_foreign_key_to_city_grade_table
 */
class m241228_052752_add_is_active_in_states_table_and_foreign_key_to_city_grade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%states}}', 'is_deleted', $this->tinyInteger()->notNull()->defaultValue(0));
        
        $this->addColumn('{{%states}}', 'is_active', $this->tinyInteger()->notNull()->defaultValue(1));

        $this->addColumn('{{%city_grades}}', 'state_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-city_grades_states-state_id', // FK name
            'city_grades',                 // Table with FK
            'state_id',                          // FK column
            'states',                                 // Referenced table
            'id',                                     // Referenced column
            'CASCADE',                                // On delete
            'CASCADE'                                 // On update
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-city_grades_states-state_id', 'city_grades');

        $this->dropColumn('{{%city_grades}}', 'state_id');

        $this->dropColumn('{{%states}}', 'is_active');

        $this->dropColumn('{{%states}}', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241228_052752_add_is_active_in_states_table_and_foreign_key_to_city_grade_table cannot be reverted.\n";

        return false;
    }
    */
}
