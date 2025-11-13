<?php

use yii\db\Migration;

/**
 * Migration to make vehicle_type field nullable in employee_punch_details table
 */
class m250901_170000_make_vehicle_type_nullable_in_employee_punch_details extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Make vehicle_type field nullable
        $this->alterColumn('{{%employee_punch_details}}', 'vehicle_type', $this->string(255)->null()->comment('Vehicle Type'));
        
        // Update the model rules to make vehicle_type not required
        echo "vehicle_type field in employee_punch_details table is now nullable.\n";
        echo "Note: You may need to update the EmployeePunchDetails model rules to remove 'vehicle_type' from required fields.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Revert vehicle_type field back to not null
        $this->alterColumn('{{%employee_punch_details}}', 'vehicle_type', $this->string(255)->notNull()->comment('Vehicle Type'));
        
        echo "vehicle_type field in employee_punch_details table is now required again.\n";
    }
}
