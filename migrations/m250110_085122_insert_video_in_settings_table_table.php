<?php

use yii\db\Migration;

/**
 * Class m250110_085122_insert_video_in_settings_table_table
 */
class m250110_085122_insert_video_in_settings_table_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%settings}}', [
            'type' => 'video_tutorials',
            'value' => '132.mp4'

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250110_085122_insert_video_in_settings_table_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250110_085122_insert_video_in_settings_table_table cannot be reverted.\n";

        return false;
    }
    */
}
