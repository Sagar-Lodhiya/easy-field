<?php

use yii\db\Migration;

class m250326_095048_update_employee_punch_log_lat_long_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%employee_punch_logs}}', 'latitude', $this->decimal(11, 8)->notNull());
        $this->alterColumn('{{%employee_punch_logs}}', 'longitude', $this->decimal(11, 8)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250326_095048_update_employee_punch_log_lat_long_type cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250326_095048_update_employee_punch_log_lat_long_type cannot be reverted.\n";

        return false;
    }
    */
}
