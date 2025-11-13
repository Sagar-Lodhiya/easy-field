<?php
namespace app\modules\api\models;


use yii\base\Model;
use yii\web\UploadedFile;
// use app\modules\api\helpers\ImageUploadHelper;
use app\models\Leave;
use app\models\LeaveType;
use app\models\Users;
use Yii;

class LeaveForm extends Model
{
    public $user_id;
    public $leave_type_id; // Automatically set to logged-in user
    public $start_date;
    public $end_date;
    public $reason;
    public $status;

    public function rules()
    {
        return [
            [['leave_type_id', 'user_id', 'start_date', 'end_date', 'reason',  ], 'required'],
            [['leave_type_id', 'user_id','status'], 'integer'],
            [['start_date', 'end_date'], 'date', 'format' => 'php:Y-m-d'],
            [['reason'], 'string'],
            [['leave_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => LeaveType::class, 'targetAttribute' => ['leave_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function saveLeaves()
    {
        
        if ($this->validate()) {
            
            $leave = new Leave();
            
            $leave->attributes = $this->attributes;

            // Set user_id to the logged-in user ID
            $leave->user_id = Yii::$app->user->id;  // Logged-in user

            // Access the related Party model through the correct relation method
            $leaveTypeName = $leave->leaveType ? $leave->leaveType->leave_type : '';  // Correct relation access

            // Handle payments photo upload using ImageUploadHelper
       
            if ($leave->save()) {
                return [
                    // 'created_at' => $payments->created_at,
                    'user_id' => $leave->user_id,
                    'name' => $leave->user->name,
                    'leave_type_id' => $leaveTypeName,
                    'start_date' => $leave->start_date,
                    'end_date' => $leave->end_date,
                    'reason' => $leave->reason,
                    'status' =>'Pending',
    
                ];
            }else{
                echo "not saved";exit;
            }
        }

        return false;
    }



}
