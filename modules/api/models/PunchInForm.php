<?php

namespace app\modules\api\models;

use app\models\EmployeePunchDetails;
use app\models\EmployeePunchLogs;
use app\models\Users;
use app\modules\api\helpers\ImageUploadHelper;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 */
class PunchInForm extends Model
{
    public $latitude;
    public $longitude;
    public $place;
    public $vehicle_type;

    public $meter_reading_in_km;
    public $battery;
    public $mobile_network;
    public $image;
    public $punch_in_type; // O for office, S for sales

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['punch_in_type'], 'required'],
            [['punch_in_type'], 'in', 'range' => ['O', 'S']],
            [['latitude', 'longitude', 'place', 'battery', 'mobile_network'], 'required', 'when' => function ($model) {
                return $model->punch_in_type === 'S';
            }],
            ['vehicle_type', 'required', 'when' => function ($model) {
                return $model->punch_in_type === 'S';
            }, 'message' => 'Vehicle Type is required for sales punch-in'],
            ['meter_reading_in_km', 'required', 'when' => function ($model) {
                return $model->punch_in_type === 'S' && $model->vehicle_type != 'Other / Bus / Train';
            }, 'message' => 'Meter Reading In Km is required'],
            ['image', 'required', 'when' => function ($model) {
                return $model->punch_in_type === 'S';
            }, 'message' => 'Image is required for sales punch-in'],
            ['image', 'file'],

            ['latitude', 'validateOneRequestPerDay'],
            // ['latitude', 'validateOfficeProximityRule'],
            // ['latitude', 'validateOfficeProximity', 'when' => function ($model) {
            //     return $model->punch_in_type === 'O';
            // }],
        ];
    }

    public function validateOneRequestPerDay($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $hasPunchedIn = EmployeePunchDetails::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->andWhere(['between', 'created_at', date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
            if ($hasPunchedIn->count() > 0) {
                $this->addError($attribute, 'You have already punched in for today.');
            }
        }
    }

    // public function validateOfficeProximity($attribute, $params)
    // {
    //     // Only validate for office users (user_type = 'office') when punch_in_type is 'O'
    //     $user = Users::find()->where(['id' => Yii::$app->user->id])->one();
    //     if (!$user || $user->user_type != 'office') {
    //         return true; // Not an office user, skip validation
    //     }

    //     // Fetch office location and allowed distance from settings
    //     $officeLocationSetting = \app\models\Settings::find()->where(['type' => 'location'])->one();
    //     $allowedDistanceSetting = \app\models\Settings::find()->where(['type' => 'distance'])->one();

    //     if ($officeLocationSetting && $allowedDistanceSetting) {
    //         $officeLocation = $officeLocationSetting->value;
    //         $allowedDistance = (float)$allowedDistanceSetting->value;

    //         list($officeLat, $officeLng) = $this->splitLatLngFromSettings($officeLocation);
    //         if ($officeLat !== null && $officeLng !== null) {
    //             $userLat = (float)$this->latitude;
    //             $userLng = (float)$this->longitude;

    //             $distance = \app\modules\api\helpers\ApplicationHelper::calculateDistance($userLat, $userLng, $officeLat, $officeLng);

    //             if ($distance > $allowedDistance) {
    //                 $this->addError('latitude', 'You are not within the allowed office area to punch in.');
    //                 return false;
    //             }
    //         }
    //     }
    //     return true;
    // }

    private function splitLatLngFromSettings($locationString)
    {
        // Assuming location string format is "latitude,longitude"
        $parts = explode(',', $locationString);
        if (count($parts) === 2) {
            $lat = trim($parts[0]);
            $lng = trim($parts[1]);
            
            if (is_numeric($lat) && is_numeric($lng)) {
                return [(float)$lat, (float)$lng];
            }
        }
        return [null, null];
    }

    /**
     * Validation rule for office proximity
     * @param string $attribute
     * @param array $params
     */
    public function validateOfficeProximityRule($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            
            // Only validate proximity for office users or sales_and_office users with punch_in_type 'O'
            if ($user && ($user->user_type == 'office' || ($user->user_type == 'sales_and_office' && $this->punch_in_type == 'O'))) {
                if (!$this->validateOfficeProximity()) {
                    // Error is already added in validateOfficeProximity method
                    return;
                }
            }
        }
    }

    /**
     * Validate office proximity for office type punches
     * @return bool
     */
    public function validateOfficeProximity()
    {
        // Fetch allowed distance (radius) from settings
        $radiusSetting = \app\models\Settings::find()->where(['type' => 'radius'])->one();
        
        if (!$radiusSetting || empty($radiusSetting->value)) {
            // If no radius setting, allow all punch-ins
            return true;
        }
        
        $allowedDistance = (float) $radiusSetting->value; // Distance in kilometers
        
        // Fetch all active offices that are not deleted
        $offices = \app\models\Office::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->all();
        
        if (empty($offices)) {
            // If no active offices, deny punch-in
            $this->addError('latitude', 'No active office locations found. Please contact administrator.');
            return false;
        }
        
        $userLat = (float) $this->latitude;
        $userLng = (float) $this->longitude;
  
        
        // Check if user is within allowed distance from any office
        foreach ($offices as $office) {
            $officeLat = (float) $office->latitude;
            $officeLng = (float) $office->longitude;
            
            $distance = \app\modules\api\helpers\ApplicationHelper::calculateDistance(
                $userLat, 
                $userLng, 
                $officeLat, 
                $officeLng
            );
            // If user is within allowed distance from any office, allow punch-in
            if ($distance <= $allowedDistance) {
                return true;
            }

        }
        
        // User is not within allowed distance from any office
        $this->addError('latitude', 'You are not within the allowed radius of any office location to punch in.');
        return false;
    }

    /**
     * Get the nearest office to the user's current location
     * @return array|null
     */
    public function getNearestOffice()
    {
        $offices = \app\models\Office::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->all();
        
        if (empty($offices)) {
            return null;
        }
        
        $userLat = (float) $this->latitude;
        $userLng = (float) $this->longitude;
        $nearestOffice = null;
        $shortestDistance = PHP_FLOAT_MAX;
        
        foreach ($offices as $office) {
            $officeLat = (float) $office->latitude;
            $officeLng = (float) $office->longitude;
            
            $distance = \app\modules\api\helpers\ApplicationHelper::calculateDistance(
                $userLat, 
                $userLng, 
                $officeLat, 
                $officeLng
            );
            
            if ($distance < $shortestDistance) {
                $shortestDistance = $distance;
                $nearestOffice = [
                    'office' => $office,
                    'distance' => $distance
                ];
            }
        }
        
        return $nearestOffice;
    }

    public function savePunchIn()
    {
        if ($this->validate()) {
            // For sales users, image is required
            if ($this->punch_in_type === 'S') {
                $imageFile = UploadedFile::getInstanceByName('image');
                
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
                    $this->addError('image', 'Image is required for sales punch-in.');
                    return false;
                }
            } else {
                $filePath = null; // No image for office users
            }

            return $this->store($filePath);
        }

        return false;
    }

    private function store($image)
    {
        $model = new EmployeePunchDetails();
        $model->user_id = Yii::$app->user->id;
        
        // Set fields based on punch_in_type
        if ($this->punch_in_type === 'S') {
            // Sales user - set all required fields and create log entry
            $model->vehicle_type = $this->vehicle_type;
            $model->punch_type = 'S'; // Sales punch type
            $model->punch_in_place = $this->place;
            $model->punch_in_image = $image;
            $model->punch_in_meter_reading_in_km = $this->meter_reading_in_km;
            
            if ($model->save()) {
                // Create log entry in employee_punch_logs table for sales users
                $logModel = new EmployeePunchLogs();
                $logModel->attendance_id = $model->id;
                $logModel->latitude = $this->latitude;
                $logModel->longitude = $this->longitude;
                $logModel->is_location_enabled = 1;
                $logModel->battery = $this->battery ?? null;
                $logModel->mobile_network = $this->mobile_network ?? null;
                $logModel->distance = 0;
    
                if ($logModel->save()) {
                    $model->punch_in_id = $logModel->id;
                    $model->last_log_id = $logModel->id;
                    if ($model->save()) {
                        $currentTime = date('Y-m-d H:i:s');
                        return [
                            'user_id' => Yii::$app->user->id,
                            'attendance_id' => $model->id,
                            'punch_in_time' => $currentTime,
                            // 'punch_in_date' => date('Y-m-d', strtotime($currentTime)),
                            'punch_in_date' =>Yii::$app->formatter->asTime($currentTime, 'php:H:i A'),
                            'punch_in_type' => $model->punch_type,
                            'vehicle_type' => $model->vehicle_type,
                            'punch_in_place' => $model->punch_in_place,
                            'meter_reading' => $model->punch_in_meter_reading_in_km,
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
                print_r($model->getErrors());
                exit;
            }
        } else {
            // Office user - set minimal required fields, NO log entry created
            $model->vehicle_type = null;
            $model->punch_type = 'O'; // Office punch type
            $model->punch_in_place = 'Office';
            $model->punch_in_image = null;
            $model->punch_in_meter_reading_in_km = null;
            
            if ($model->save()) {
                $currentTime = date('Y-m-d H:i:s');
                return [
                    'user_id' => Yii::$app->user->id,
                    'attendance_id' => $model->id,
                    'punch_in_time' => $currentTime,
                    'punch_in_date' => date('Y-m-d', strtotime($currentTime)),
                    'punch_in_type' => $model->punch_type,
                    'vehicle_type' => $model->vehicle_type,
                    'punch_in_place' => $model->punch_in_place,
                    'meter_reading' => $model->punch_in_meter_reading_in_km,
                ];
            } else {
                print_r($model->getErrors());
                exit;
            }
        }
    }
}
