<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cms}}`.
 */
class m250110_072452_create_cms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cms}}', [
            'id' => $this->primaryKey(),
            'page' => $this->string(150)->notNull(),
            'title' => $this->string(150)->notNull(),
            'description' => $this->text()->notNull()
        ]);

        $this->insert('{{%cms}}', [
            "id" => 1,
            "page"=> 'Terms And Conditions',
            "title"=> 'Terms And Conditions',
            "description"=> 'Terms And Conditions',
        ]);
       
        $this->insert('{{%cms}}', [
            "id" => 2,
            "page"=> 'Privacy Policy',
            "title"=> 'Privacy Policy',
            "description"=> 'Privacy Policy',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cms}}');
    }
}
