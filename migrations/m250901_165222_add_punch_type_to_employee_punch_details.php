<?php

use yii\db\Migration;

class m250901_165222_add_punch_type_to_employee_punch_details extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%employee_punch_details}}', 'punch_type', $this->string(1)->notNull()->defaultValue('S')->comment('S=Sales, O=Office'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%employee_punch_details}}', 'punch_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250901_165222_add_punch_type_to_employee_punch_details cannot be reverted.\n";

        return false;
    }
    */
}
