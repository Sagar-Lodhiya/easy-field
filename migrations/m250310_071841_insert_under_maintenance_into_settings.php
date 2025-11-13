<?php

use yii\db\Migration;

/**
 * Class m250310_071841_insert_under_maintenance_into_settings
 */
class m250310_071841_insert_under_maintenance_into_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'type' => 'under_maintenance',
            'value' => 0, // Default value
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250310_071841_insert_under_maintenance_into_settings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250310_071841_insert_under_maintenance_into_settings cannot be reverted.\n";

        return false;
    }
    */
}
