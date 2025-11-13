<?php

use yii\db\Migration;

class m250328_065824_add_foreign_key_to_city_grades_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add foreign key for `grade_id` column in `city_grades`
        $this->addForeignKey(
            'fk-city_grades-grade_id', // Foreign key name
            '{{%city_grades}}', // Current table
            'grade_id', // Column in current table
            '{{%grades}}', // Referenced table
            'id', // Column in referenced table
            'CASCADE', // ON DELETE
            'CASCADE' // ON UPDATE
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign key
        $this->dropForeignKey('fk-city_grades-grade_id', '{{%city_grades}}');
    }
}
