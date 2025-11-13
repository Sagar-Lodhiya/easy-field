<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%admins}}`.
 */
class m241213_065902_add_type_column_to_admins_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%admins}}', 'type', $this->integer()->notNull()->defaultValue(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%admins}}', 'type');
    }
}
