<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee_punch_logs".
 *
 * @property int $id
 * @property int|null $attendance_id
 * @property float $latitude
 * @property float $longitude
 * @property int|null $is_location_enabled
 * @property int $battery
 * @property string $mobile_network
 * @property float $distance
 * @property string $created_at
 *
 * @property EmployeePunchDetails $attendance   
 * @property EmployeePunchDetails[] $employeePunchDetails
 * @property EmployeePunchDetails[] $employeePunchDetails0
 * @property EmployeePunchDetails[] $employeePunchDetails1
 */
class EmployeePunchLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_punch_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attendance_id', 'is_location_enabled', 'battery'], 'integer'],
            [['latitude', 'longitude', 'battery', 'mobile_network', 'distance'], 'required'],
            [['latitude', 'longitude', 'distance'], 'number'],
            [['created_at'], 'safe'],
            [['mobile_network'], 'string'],
            [['attendance_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePunchDetails::class, 'targetAttribute' => ['attendance_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attendance_id' => 'Attendance ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'is_location_enabled' => 'Is Location Enabled',
            'battery' => 'Battery',
            'mobile_network' => 'Mobile Network',
            'distance' => 'Distance',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Attendance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttendance()
    {
        return $this->hasOne(EmployeePunchDetails::class, ['id' => 'attendance_id']);
    }

    /**
     * Gets query for [[EmployeePunchDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePunchDetails()
    {
        return $this->hasMany(EmployeePunchDetails::class, ['last_log_id' => 'id']);
    }

    /**
     * Gets query for [[EmployeePunchDetails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePunchDetails0()
    {
        return $this->hasMany(EmployeePunchDetails::class, ['punch_in_id' => 'id']);
    }

    /**
     * Gets query for [[EmployeePunchDetails1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePunchDetails1()
    {
        return $this->hasMany(EmployeePunchDetails::class, ['punch_out_id' => 'id']);
    }
}
