<?php

namespace app\modules\api\models;

use app\modules\api\helpers\ImageUploadHelper;
use app\models\EmployeePunchDetails;
use app\models\EmployeePunchLogs;
use app\models\Settings;
use app\models\Users;
use app\modules\api\helpers\ApplicationHelper;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LogForm extends Model
{
    public $latitude;
    public $longitude;
    public $is_location_enabled;
    public $battery;
    public $mobile_network;
    public $punch_in_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['latitude', 'longitude', 'is_location_enabled', 'battery', 'mobile_network', 'punch_in_id'], 'required'],
            ['punch_in_id', 'validatePunchInId']
        ];
    }

    public function validatePunchInId($attribute, $params)
    {
        if (!$this->hasErrors()) {
            
            $punchModel = EmployeePunchDetails::find()->where(['id' => $this->punch_in_id, 'user_id' => Yii::$app->user->id])->one();

            if ($punchModel === null || $punchModel->punch_out_id !== null) {

                $this->addError($attribute, 'Invalid Punch In id.');
            }
        }
    }

    public function saveLog()
    {
        if ($this->validate()) {

            return $this->store();
        }

        return false;
    }

    private function store()
    {
        $lastLogModel = EmployeePunchLogs::find()->where(['attendance_id' => $this->punch_in_id])->orderBy(['id'=> SORT_DESC])->one();

        $logModel = new EmployeePunchLogs();
        $logModel->attendance_id = $this->punch_in_id;
        $logModel->latitude = $this->latitude;
        $logModel->longitude = $this->longitude;
        $logModel->is_location_enabled = $this->is_location_enabled;
        $logModel->battery = $this->battery;
        $logModel->mobile_network = $this->mobile_network;
        $logModel->distance = ApplicationHelper::calculateDistance($lastLogModel->latitude,$lastLogModel->longitude,$this->latitude, $this->longitude);

        if ($logModel->save()) {
            $attendanceModel = EmployeePunchDetails::findOne($this->punch_in_id);
            $attendanceModel->last_log_id = $logModel->id;
            $attendanceModel->total_distance += $logModel->distance;
            if($attendanceModel->save()){
                return true;
            }
        } else {
            print_r($logModel->getErrors());exit;
        }

        return false;
    }
}
