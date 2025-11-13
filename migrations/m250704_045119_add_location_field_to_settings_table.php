<?php

use yii\db\Migration;

class m250704_045119_add_location_field_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'id' => 10,
            'type' => 'location',
            'value' => '22.5726, 88.3639',
        ]);
        $this->insert('settings', [
            'id' => 11,
            'type' => 'distance',
            'value' => '0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('settings', ['type' => 'location']);
        $this->delete('settings', ['type' => 'distance']);
    }

}
