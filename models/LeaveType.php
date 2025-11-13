<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "leave_type".
 *
 * @property int $id
 * @property string $leave_type
 * @property int $is_active
 * @property int $is_deleted
 */
class LeaveType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leave_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['leave_type'], 'required'],
            [['leave_type'], 'string', 'max' => 255],
            [['is_active', 'is_deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'leave_type' => 'Leave Type',
        ];
    }

    public static function getLeaveTypeList(){
        $model = LeaveType::find()
        ->orderBy(['leave_type' => SORT_ASC])
        ->all();
        $list = ArrayHelper::map($model,'id','leave_type');
        return $list;
    }
}
