<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%settings}}`.
 */
class m250829_054918_add_radius_column_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%settings}}', [            
            'type' => 'radius',
            'value' => ''
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%settings}}', ['type' => 'radius']);
    }
}
