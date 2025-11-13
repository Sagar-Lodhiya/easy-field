<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%grade}}`.
 */
class m241129_114848_create_grade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%grades}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ], 'ENGINE=InnoDB');

        $this->insert('{{%grades}}', [
            'name' => 'A',
        ], 'ENGINE=InnoDB');
        
        $this->insert('{{%grades}}', [
            'name' => 'B',
        ], 'ENGINE=InnoDB');
        
        $this->insert('{{%grades}}', [
            'name' => 'C',
        ], 'ENGINE=InnoDB');
        
        $this->insert('{{%grades}}', [
            'name' => 'D',
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%grades}}');
    }
}
