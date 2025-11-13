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
 * StartTrackingForm is the model for starting location tracking
 */
class StartTrackingForm extends Model
{
    public $place;
    public $meter_reading_in_km;
    public $punch_in_image;
    public $vehicle_type;
    public $latitude;
    public $longitude;
    public $battery;
    public $mobile_network;
    public $image;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['place', 'vehicle_type', 'latitude', 'longitude', 'battery', 'mobile_network'], 'required'],
            [['meter_reading_in_km'], 'number'],
            ['meter_reading_in_km', 'required', 'when' => function ($model) {
                return $model->vehicle_type !== 'Other / Bus / Train';
            }, 'whenClient' => "function (attribute, value) {
                return $('#starttrackingform-vehicle_type').val() !== 'Other / Bus / Train';
            }", 'message' => 'Meter Reading In Km is required'],
            [['image'], 'file', 'extensions' => 'jpg, jpeg, png', 'skipOnEmpty' => true],
            [['place', 'vehicle_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array attribute labels
     */
    public function attributeLabels()
    {
        return [
            'place' => 'Punch In Place',
            'meter_reading_in_km' => 'Meter Reading In Km',
            'punch_in_image' => 'Punch In Image',
            'vehicle_type' => 'Vehicle Type',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'battery' => 'Battery',
            'mobile_network' => 'Mobile Network',
        ];
    }

    /**
     * Check if user can start tracking
     * @param int|null $punchDetailsId Optional specific punch details ID to check
     * @return bool
     */
    public function canStartTracking($punchDetailsId = null)
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return false;
        }

        // Check if user is punched in and get the specific punch details
        $punchDetails = $this->getCurrentPunchDetails($punchDetailsId);

        if (!$punchDetails) {
            return false;
        }

        // Check if user type is 'sales_and_office' and punch type is 'O'
        if ($user->user_type == 'sales_and_office' && $punchDetails->punch_type == 'O') {
            return true;
        }

        return false;
    }

    /**
     * Get current punch details for the user
     * @param int|null $punchDetailsId Optional specific punch details ID to find
     * @return EmployeePunchDetails|null
     */
    public function getCurrentPunchDetails($punchDetailsId = null)
    {
        $user = Yii::$app->user->identity;
        if (!$user) {
            return null;
        }

        $query = EmployeePunchDetails::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['punch_out_id' => null]);

        // If specific ID is provided, find by that ID
        if ($punchDetailsId !== null) {
            $query->andWhere(['id' => $punchDetailsId]);
        }

        return $query->one();
    }

    /**
     * Start tracking by updating employee punch details
     * @param int|null $punchDetailsId Optional specific punch details ID to start tracking for
     * @return array|false
     */
    public function startTracking($punchDetailsId = null)
    {
        if (!$this->validate()) {
            return false;
        }

        if (!$this->canStartTracking($punchDetailsId)) {
            $this->addError('general', 'You cannot start tracking. Please check your punch-in status and user type.');
            return false;
        }

        // Get current punch details using the helper method
        $punchDetails = $this->getCurrentPunchDetails($punchDetailsId);

        if (!$punchDetails) {
            $this->addError('general', 'No active punch-in found.');
            return false;
        }

        // Handle image upload if provided
        $imagePath = null;
        $imageFile = UploadedFile::getInstanceByName('image');

        if ($imageFile) {
            $validation = ImageUploadHelper::validateImage($imageFile);
            if (!$validation) {
                $this->addError('image', 'Invalid image file');
                return false;
            }

            $imagePath = ImageUploadHelper::uploadImage($imageFile);
            if (!$imagePath) {
                $this->addError('image', 'Failed to upload image');
                return false;
            }
        }

        // Update the punch details
        $punchDetails->punch_in_place = $this->place;
        $punchDetails->vehicle_type = $this->vehicle_type;
        $punchDetails->punch_in_meter_reading_in_km = $this->meter_reading_in_km;
        $punchDetails->punch_in_image = $imagePath;

        if ($punchDetails->save()) {
            $logModel = new EmployeePunchLogs();
            $logModel->attendance_id = $punchDetails->id;
            $logModel->latitude = $this->latitude;
            $logModel->longitude = $this->longitude;
            $logModel->is_location_enabled = 1;
            $logModel->battery = $this->battery ?? null;
            $logModel->mobile_network = $this->mobile_network ?? null;
            $logModel->distance = 0;

            if ($logModel->save()) {
                $punchDetails->punch_in_id = $logModel->id;
                $punchDetails->last_log_id = $logModel->id;
                if ($punchDetails->save()) {
                    return [
                        'success' => true,
                        'message' => 'Tracking started successfully',
                        'data' => [
                            'punch_in_id' => $punchDetails->punch_in_id,
                            'attendance_id' => $punchDetails->id,
                            'punch_in_place' => $punchDetails->punch_in_place,
                            'vehicle_type' => $punchDetails->vehicle_type,
                            'punch_in_meter_reading_in_km' => $punchDetails->punch_in_meter_reading_in_km,
                            'punch_in_image' => $punchDetails->punch_in_image,
                        ]
                    ];
                } else {
                    print_r($punchDetails->getErrors());
                    exit;
                }
            } else {
                print_r($logModel->getErrors());
                exit;
            }
        } else {
            $this->addError('general', 'Failed to update punch details');
            return false;
        }
    }
}
