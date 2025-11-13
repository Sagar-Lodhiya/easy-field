<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee_punch_logs}}`.
 */
class m250101_090430_create_employee_punch_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee_punch_details}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'punch_in_id' => $this->integer(11)->defaultValue(null),
            'punch_in_place' => $this->string(250)->defaultValue(null),
            'punch_in_meter_reading_in_km' => $this->string(250)->defaultValue(null),
            'punch_in_image' => $this->string(250)->defaultValue(null),
            'punch_out_id' => $this->integer(11)->defaultValue(null),
            'punch_out_place' => $this->string(250)->defaultValue(null),
            'punch_out_meter_reading_in_km' => $this->string(250)->defaultValue(null),
            'punch_out_image' => $this->string(250)->defaultValue(null),
            'last_log_id' => $this->integer(11)->defaultValue(null),
            'vehicle_type' => $this->string()->notNull(),
            'tour_details' => $this->string()->defaultValue(null),
            'partner_name' => $this->string()->defaultValue(null),
            'traveled_km' => $this->float()->defaultValue(null),
            'total_distance' => $this->float()->defaultValue(null),
            'da' => $this->float()->defaultValue(null),
            'ta' => $this->float()->defaultValue(null),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        $this->createTable('{{%employee_punch_logs}}', [
            'id' => $this->primaryKey(),
            'attendance_id' => $this->integer(11)->defaultValue(null),
            'latitude' => $this->float()->notNull(),
            'longitude' => $this->float()->notNull(),
            'is_location_enabled' => $this->tinyInteger(1)->defaultValue(1),
            'battery' => $this->integer(11)->notNull(),
            'mobile_network' => $this->string(10)->notNull(),
            'distance' => $this->float()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');


        $this->addForeignKey(
            'fk-employee_punch_details-user_id',
            '{{%employee_punch_details}}',
            'user_id',
            '{{%users}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-employee_punch_details-punch_in_id',
            '{{%employee_punch_details}}',
            'punch_in_id',
            '{{%employee_punch_logs}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-employee_punch_details-punch_out_id',
            '{{%employee_punch_details}}',
            'punch_out_id',
            '{{%employee_punch_logs}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-employee_punch_details-last_log_id',
            '{{%employee_punch_details}}',
            'last_log_id',
            '{{%employee_punch_logs}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-employee_punch_logs-attendance_id',
            '{{%employee_punch_logs}}',
            'attendance_id',
            '{{%employee_punch_details}}', // Assuming there's an 'employees' table with the 'id' column
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-employee_punch_logs-attendance_id',
            '{{%employee_punch_logs}}'
        );

        $this->dropForeignKey(
            'fk-employee_punch_details-last_log_id',
            '{{%employee_punch_details}}'
        );
        
        $this->dropForeignKey(
            'fk-employee_punch_details-punch_out_id',
            '{{%employee_punch_details}}'
        );

        $this->dropForeignKey(
            'fk-employee_punch_details-punch_in_id',
            '{{%employee_punch_details}}'
        );
        
        $this->dropForeignKey(
            'fk-employee_punch_details-user_id',
            '{{%employee_punch_details}}'
        );

        $this->dropTable('{{%employee_punch_logs}}');
        
        $this->dropTable('{{%employee_punch_details}}');
    }
}
