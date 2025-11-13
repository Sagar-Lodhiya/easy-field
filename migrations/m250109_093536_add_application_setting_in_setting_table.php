<?php

use yii\db\Migration;

/**
 * Class m250109_093536_add_application_setting_in_setting_table
 */
class m250109_093536_add_application_setting_in_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%settings}}', [
            'type' => 'app_version',
            'value' => '1.0.0'

        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250109_093536_add_application_setting_in_setting_table cannot be reverted.\n";

        return true;
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250109_093536_add_application_setting_in_setting_table cannot be reverted.\n";

        return false;
    }
    */
}
