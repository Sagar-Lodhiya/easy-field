<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "leave".
 *
 * @property int $id
 * @property int $leave_type_id
 * @property int $user_id
 * @property int $status
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property int $created_at
 * @property int $updated_at
 *
 * @property LeaveType $leaveType
 * @property Users $user
 */
class Leave extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leave';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['leave_type_id', 'user_id', 'start_date', 'end_date', 'reason',  ], 'required'],
            [['leave_type_id', 'user_id', 'status'], 'integer'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            [['reason'], 'string'],
            [['leave_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::class, 'targetAttribute' => ['leave_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leave_type_id' => 'Leave Type ID',
            'user_id' => 'User ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'reason' => 'Reason',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[LeaveType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLeaveType()
    {
        return $this->hasOne(LeaveType::class, ['id' => 'leave_type_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
