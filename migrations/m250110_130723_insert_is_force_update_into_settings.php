<?php

use yii\db\Migration;

/**
 * Class m250110_130723_insert_is_force_update_into_settings
 */
class m250110_130723_insert_is_force_update_into_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'type' => 'is_force_update',
            'value' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250110_130723_insert_is_force_update_into_settings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250110_130723_insert_is_force_update_into_settings cannot be reverted.\n";

        return false;
    }
    */
}
