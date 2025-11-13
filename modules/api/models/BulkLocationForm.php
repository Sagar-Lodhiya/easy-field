<?php

namespace app\modules\api\models;

use app\models\EmployeePunchDetails;
use app\models\EmployeePunchLogs;
use app\modules\api\helpers\ApplicationHelper;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class BulkLocationForm extends Model
{
    public $locations;
    public $punch_in_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['locations'], 'required'],
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

            return $this->storeLocations();
        }

        return false;
    }


    private function storeLocations()
    {

        $lastLogModel = EmployeePunchLogs::find()->where(['attendance_id' => $this->punch_in_id])->orderBy(['id' => SORT_DESC])->one();
        
        $distance = 0;
        
        $lastLogId = $lastLogModel->id;
        
        foreach ($this->locations as $logs) {
            $lastSavedLocationModel = EmployeePunchLogs::find()->where(['id' => $lastLogId])->one();

            $logModel = new EmployeePunchLogs();
            $logModel->attendance_id = $this->punch_in_id;
            $logModel->latitude = $logs['latitude'];
            $logModel->longitude = $logs['longitude'];
            $logModel->is_location_enabled = $logs['is_location_enabled'];
            $logModel->battery = (Int)$logs['battery'];
            $logModel->mobile_network = $logs['mobile_network'];
            $logModel->distance = ApplicationHelper::calculateDistance($lastSavedLocationModel->latitude, $lastSavedLocationModel->longitude, $logs['latitude'], $logs['longitude']);
            if ($logModel->save()) {
                $distance += $logModel->distance;
                $lastLogId = $logModel->id;
            } else {
                print_r($logModel->getErrors());
                exit;
            }
        }

        $attendanceModel = EmployeePunchDetails::findOne($this->punch_in_id);
        $attendanceModel->last_log_id = $lastLogId;
        $attendanceModel->total_distance += $distance;
        if ($attendanceModel->save()) {
            return true;
        }
    }
}