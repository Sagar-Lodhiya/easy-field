<?php

namespace app\modules\api\models;

use app\models\Expenses;
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
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class PunchOutForm extends Model
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
            [['latitude', 'longitude', 'place', 'tour_details'], 'required', 'when' => function ($model) {
                return $model->_punchDetails && $model->_punchDetails->punch_type === 'S';
            }],
            [['battery', 'mobile_network'], 'required', 'when' => function ($model) {
                return $model->_punchDetails && $model->_punchDetails->punch_type === 'S';
            }],
            // password is validated by validatePassword()
            ['latitude', 'shouldPunchedIn'],
            ['meter_reading_in_km', 'required', 'when' => function ($model) {
                return $model->_punchDetails && 
                       $model->_punchDetails->punch_type === 'S' && 
                       $model->_punchDetails->vehicle_type != 'Other / Bus / Train';
            }, 'message' => 'Meter Reading In Km is required'],
            ['meter_reading_in_km', 'validateMeterReading'],
            ['image', 'required', 'when' => function ($model) {
                return $model->_punchDetails && $model->_punchDetails->punch_type === 'S';
            }, 'message' => 'Image is required for sales punch-out'],
            ['image', 'file'],
            ['partner_name', 'string']
        ];
    }

    public function validateMeterReading($attribute, $params)
{
    if ($this->_punchDetails && isset($this->_punchDetails->punch_in_meter_reading_in_km)) {
        $previousReading = $this->_punchDetails->punch_in_meter_reading_in_km;
        
        if ($this->$attribute <= $previousReading) {
            $this->addError($attribute, "Meter Reading must be greater than $previousReading km.");
        }
    }
}

    public function init()
    {
        parent::init();
        $model = EmployeePunchDetails::find()
            ->where(['user_id' => Yii::$app->user->id, 'punch_out_id' => null])->one();
        // Initialize the object
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

    public function savePunchOut()
    {
        if ($this->validate()) {
            $imageFile = UploadedFile::getInstanceByName('image');
            $filePath = null;

            // Only process image for sales punch type (S)
            if ($this->_punchDetails && $this->_punchDetails->punch_type === 'S') {
                if ($imageFile) {
                    $validation = ImageUploadHelper::validateImage($imageFile);

                    if (!$validation) {
                        $this->addError('image', 'Invalid File image');
                        return false;
                    }

                    $filePath = ImageUploadHelper::uploadImage($imageFile);

                    if (!$filePath) {
                        $this->addError('image', 'Failed to upload image.');
                        return false;
                    }
                } else {
                    $this->addError('image', 'Image is required for sales punch-out.');
                    return false;
                }
            }

            return $this->store($filePath);
        }

        return false;
    }

    private function store($image)
    {
        $model = EmployeePunchDetails::findOne($this->_punchDetails->id);

        // Calculate distance only for Sales punch type (S) as Office punch type (O) doesn't have lastLog
        $distance = 0;
        if ($model->punch_type === 'S' && $model->lastLog && $this->latitude && $this->longitude) {
            $distance = ApplicationHelper::calculateDistance($model->lastLog->latitude, $model->lastLog->longitude, $this->latitude, $this->longitude);
        }

        // Set fields based on punch type
        if ($model->punch_type === 'S') {
            // Sales punch type - set all fields
            $model->punch_out_place = $this->place;
            $model->punch_out_image = $image;
            $model->punch_out_meter_reading_in_km = $this->meter_reading_in_km;
            $model->tour_details = $this->tour_details;
            $model->partner_name = $this->partner_name;
            $model->traveled_km = (float)$this->meter_reading_in_km - (float)$this->_punchDetails->punch_in_meter_reading_in_km;
            $model->total_distance += $distance;
            $model->da = $this->calculateDailyAllowance($model->traveled_km);
            $model->ta = $this->calculateTravellingAllowance($model->traveled_km);
        } else {
            // Office punch type - set minimal fields
            $model->punch_out_place = 'Office';
            $model->punch_out_image = null;
            $model->punch_out_meter_reading_in_km = null;
            $model->tour_details = null;
            $model->partner_name = null;
            $model->traveled_km = 0;
            $model->total_distance = 0;
            $model->da = 0;
            $model->ta = 0;
        }
        if ($model->save()) {
            // Create log entry for Sales punch type (S) when location data is provided
            if ($model->punch_type === 'S' && $this->latitude && $this->longitude) {
                $logModel = new EmployeePunchLogs();
                $logModel->attendance_id = $model->id;
                $logModel->latitude = $this->latitude;
                $logModel->longitude = $this->longitude;
                $logModel->is_location_enabled = 1;
                $logModel->battery = $this->battery;
                $logModel->mobile_network = $this->mobile_network;
                $logModel->distance = $distance;
                if ($logModel->save()) {
                    $model->punch_out_id = $logModel->id;
                    if ($model->save()) {
                        return [
                            "user_id" => Yii::$app->user->id,
                            "attendance_id" => $model->id,
                        ];
                    } else {
                        print_r($model->getErrors());
                        exit;
                    }
                } else {
                    print_r($logModel->getErrors());
                    exit;
                }
            } else {
                // For Office punch type (O) or Sales punch type (S) without location data
                // Create a minimal log entry to properly complete the punch out
                $logModel = new EmployeePunchLogs();
                $logModel->attendance_id = $model->id;
                $logModel->latitude = $this->latitude ?? 0;
                $logModel->longitude = $this->longitude ?? 0;
                $logModel->is_location_enabled = 0;
                $logModel->battery = $this->battery ?? 0;
                $logModel->mobile_network = $this->mobile_network ?? 'N/A';
                $logModel->distance = $distance;
                
                if ($logModel->save()) {
                    $model->punch_out_id = $logModel->id;
                    if ($model->save()) {
                        return [
                            "user_id" => Yii::$app->user->id,
                            "attendance_id" => $model->id,
                        ];
                    } else {
                        print_r($model->getErrors());
                        exit;
                    }
                } else {
                    print_r($logModel->getErrors());
                    exit;
                }
            }
        } else {
            print_r($model->getErrors());
            exit;
        }
    }

    private function calculateTravellingAllowance($distance)
    {
        $settingModel = Settings::find()->where(['type' => ($this->_punchDetails->punch_in_meter_reading_in_km == 'car') ? Yii::$app->params['travellingRateCarKey'] : Yii::$app->params['travellingRateBikeKey']])->one();
        $taAmount = $distance * (float)$settingModel->value;
        
        // save expense here for travelling allowance
        $expense = new Expenses();
        $expense->status_id = 4;
        $expense->user_id = Yii::$app->user->id;  // Logged-in user
        $expense->admin_id = Yii::$app->user->identity->parent_id;  // Parent of the user
        $expense->expense_type = 'fixed';  // Parent of the user
        $expense->category_id = 1;
        $expense->requested_amount = $taAmount;
        $expense->approved_amount = $taAmount;
        $expense->expense_photo = 'fixed-expense.png';
        $expense->expense_details = 'Travelling Allowance expense for date: ' . date('Y-m-d');
        $expense->is_night_stay = $this->is_night_stay ?? 0;
        $expense->expense_date = date('Y-m-d');

        if ($expense->save()) {    
            return $taAmount;
        } else {
            print_r($expense->getErrors());
            exit;
        }

        return 0;
    }

    private function calculateDailyAllowance($distance)
    {

        $userModel = Users::findOne(Yii::$app->user->id);

        if ($userModel->eligible_km <= $distance) {

            // save expense here for daily allowance
            $amount = $this->is_night_stay ? $userModel->da_with_night_stay : $userModel->da_amount;

            $expense = new Expenses();
            $expense->status_id = 4;
            $expense->user_id = Yii::$app->user->id;  // Logged-in user
            $expense->admin_id = Yii::$app->user->identity->parent_id;  // Parent of the user
            $expense->expense_type = 'fixed';  // Parent of the user
            $expense->category_id = 1;
            $expense->requested_amount = $amount;
            $expense->approved_amount = $amount;
            $expense->expense_photo = 'fixed-expense.png';
            $expense->expense_details = 'Daily Allowance expense for date: ' . date('Y-m-d');
            $expense->is_night_stay = $this->is_night_stay ?? 0;
            $expense->expense_date = date('Y-m-d');

            if ($expense->save()) {
                return $amount;
            } else {
                print_r($expense->getErrors());
                exit;
            }
        }

        return 0;
    }
}
