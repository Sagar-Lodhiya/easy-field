<?php

use yii\db\Migration;

class m250901_165923_add_is_deleted_to_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%office}}', 'is_deleted', $this->tinyInteger(1)->notNull()->defaultValue(0)->after('is_active'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%office}}', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250901_165923_add_is_deleted_to_office_table cannot be reverted.\n";

        return false;
    }
    */
}
