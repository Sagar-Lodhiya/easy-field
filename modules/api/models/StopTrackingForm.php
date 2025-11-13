<?php

namespace app\modules\api\models;

use app\modules\api\helpers\ApplicationHelper;
use app\modules\api\helpers\ImageUploadHelper;
use app\models\EmployeePunchDetails;
use app\models\EmployeePunchLogs;
use app\models\Settings;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * StopTrackingForm is the model for stopping tracking without punching out.
 *
 * @property-read User|null $user
 *
 */
class StopTrackingForm extends Model
{
    public $latitude;
    public $longitude;
    public $place;
    public $meter_reading_in_km;
    public $tour_details;
    public $is_night_stay;
    public $partner_name;
    public $battery;
    public $mobile_network;
    public $image;
    public $_punchDetails;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['latitude', 'longitude', 'place', 'tour_details', 'battery', 'mobile_network'], 'required'],
            // password is validated by validatePassword()
            ['latitude', 'shouldPunchedIn'],
            ['meter_reading_in_km', 'required', 'when' => function ($model) {
                return $model->_punchDetails ? $model->_punchDetails->vehicle_type != 'Other / Bus / Train' : false;
            }, 'message' => 'Meter Reading In Km is required'],
            ['image', 'file'],
            ['partner_name', 'string']
        ];
    }

    public function init()
    {
        parent::init();
        $model = EmployeePunchDetails::find()
            ->where(['user_id' => Yii::$app->user->id, 'punch_out_id' => null])->one();
        $this->_punchDetails = $model;
    }

    public function shouldPunchedIn($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if ($this->_punchDetails == null) {
                $this->addError($attribute, 'Please Punch in first.');
            }
        }
    }

    public function saveStopTracking()
    {
        if ($this->validate()) {
            $imageFile = UploadedFile::getInstanceByName('image');
            $validation = ImageUploadHelper::validateImage($imageFile);

            if (!$validation) {
                $this->addError('image', 'Invalid File image');
                return false;
            }

            $filePath = ImageUploadHelper::uploadImage($imageFile);

            if ($filePath) {
                return $this->store($filePath);
            } else {
                $this->addError('image', 'Failed to upload image.');
                return false;
            }
        }

        return false;
    }

    private function store($image)
    {
        $model = EmployeePunchDetails::findOne($this->_punchDetails->id);

        $distance = ApplicationHelper::calculateDistance(
            $model->lastLog->latitude,
            $model->lastLog->longitude,
            $this->latitude,
            $this->longitude
        );

        // Update the punch details but don't set punch_out_id
        $model->punch_out_place = $this->place;
        $model->punch_out_image = $image;
        $model->punch_out_meter_reading_in_km = $this->meter_reading_in_km;
        $model->tour_details = $this->tour_details;
        $model->partner_name = $this->partner_name;
        $model->traveled_km = (float)$this->meter_reading_in_km - (float)$this->_punchDetails->punch_in_meter_reading_in_km;
        $model->total_distance += $distance;
        $model->da = $this->calculateDailyAllowance($model->traveled_km);
        $model->ta = $this->calculateTravellingAllowance($model->traveled_km);
        
        if ($model->save()) {
            // Create a new punch log entry for tracking purposes
            $logModel = new EmployeePunchLogs();
            $logModel->attendance_id = $model->id;
            $logModel->latitude = $this->latitude;
            $logModel->longitude = $this->longitude;
            $logModel->is_location_enabled = 1;
            $logModel->battery = $this->battery;
            $logModel->mobile_network = $this->mobile_network;
            $logModel->distance = $distance;

            if ($logModel->save()) {
                // Note: We don't set punch_out_id here - this is the key difference
                // The employee remains "punched in" but tracking is stopped
                
                return [
                    "user_id" => Yii::$app->user->id,
                    "attendance_id" => $model->punch_in_id,
                    "message" => "Tracking stopped successfully. Employee remains punched in.",
                    "log_id" => $logModel->id
                ];
            } else {
                print_r($logModel->getErrors());
                exit;
            }
        } else {
            print_r($model->getErrors());
            exit;
        }
    }

    private function calculateTravellingAllowance($distance)
    {
        $settingModel = Settings::find()->where(['type' => ($this->_punchDetails->punch_in_meter_reading_in_km == 'car') ? Yii::$app->params['travellingRateCarKey'] : Yii::$app->params['travellingRateBikeKey']])->one();

        $daAmount = $distance * (float)$settingModel->value;

        // save expence here for travelling allowance

        return $daAmount;
    }

    private function calculateDailyAllowance($distance)
    {
        $userModel = Users::findOne(Yii::$app->user->id);

        if ($userModel->eligible_km <= $distance) {
            // save expence here for daily allowance

            $amount = $this->is_night_stay ? $userModel->da_with_night_stay : $userModel->da_amount;
            return $amount;
        }

        return 0;
    }
}
