<?php

use yii\db\Migration;

class m250703_131404_add_office_punchin_enabled_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'office_punchin_enabled', $this->tinyInteger(1)->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'office_punchin_enabled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250703_131404_add_office_punchin_enabled_to_users_table cannot be reverted.\n";

        return false;
    }
    */
}
