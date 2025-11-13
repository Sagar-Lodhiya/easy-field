<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "employee_punch_details".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $punch_in_id
 * @property string|null $punch_in_place
 * @property string|null $punch_in_meter_reading_in_km
 * @property string|null $punch_in_image
 * @property int|null $punch_out_id
 * @property string|null $punch_out_place
 * @property string|null $punch_out_meter_reading_in_km
 * @property string|null $punch_out_image
 * @property int|null $last_log_id
 * @property string $vehicle_type
 * @property string $punch_type
 * @property string|null $tour_details
 * @property string|null $partner_name
 * @property float|null $traveled_km
 * @property float|null $total_distance
 * @property float|null $da
 * @property float|null $ta
 * @property string $created_at
 * @property string $updated_at
 *
 * @property EmployeePunchLogs[] $employeePunchLogs
 * @property EmployeePunchLogs $lastLog
 * @property EmployeePunchLogs $punchIn
 * @property EmployeePunchLogs $punchOut
 * @property Users $user
 */
class EmployeePunchDetails extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_punch_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'punch_type'], 'required'],
            ['vehicle_type', 'required', 'when' => function ($model) {
                return $model->punch_type === 'S';
            }, 'message' => 'Vehicle Type is required for sales punch-in'],
            [['user_id', 'punch_in_id', 'punch_out_id', 'last_log_id'], 'integer'],
            [['traveled_km', 'total_distance', 'da', 'ta'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['punch_in_place', 'punch_in_meter_reading_in_km', 'punch_in_image', 'punch_out_place', 'punch_out_meter_reading_in_km', 'punch_out_image'], 'string', 'max' => 250],
            [['vehicle_type', 'tour_details', 'partner_name'], 'string', 'max' => 255],
            [['punch_type'], 'string', 'max' => 1],
            [['punch_type'], 'in', 'range' => ['S', 'O']],
            [['last_log_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePunchLogs::class, 'targetAttribute' => ['last_log_id' => 'id']],
            [['punch_in_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePunchLogs::class, 'targetAttribute' => ['punch_in_id' => 'id']],
            [['punch_out_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePunchLogs::class, 'targetAttribute' => ['punch_out_id' => 'id']],
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
            'user_id' => 'User ID',
            'punch_in_id' => 'Punch In ID',
            'punch_in_place' => 'Punch In Place',
            'punch_in_meter_reading_in_km' => 'Punch In Meter Reading In Km',
            'punch_in_image' => 'Punch In Image',
            'punch_out_id' => 'Punch Out ID',
            'punch_out_place' => 'Punch Out Place',
            'punch_out_meter_reading_in_km' => 'Punch Out Meter Reading In Km',
            'punch_out_image' => 'Punch Out Image',
            'last_log_id' => 'Last Log ID',
            'vehicle_type' => 'Vehicle Type',
            'punch_type' => 'Punch Type',
            'tour_details' => 'Tour Details',
            'partner_name' => 'Partner Name',
            'traveled_km' => 'Traveled Km',
            'total_distance' => 'Total Distance',
            'da' => 'Da',
            'ta' => 'Ta',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[EmployeePunchLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePunchLogs()
    {
        return $this->hasMany(EmployeePunchLogs::class, ['attendance_id' => 'id']);
    }

    /**
     * Gets query for [[LastLog]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLastLog()
    {
        return $this->hasOne(EmployeePunchLogs::class, ['id' => 'last_log_id']);
    }


    /**
     * Gets query for [[PunchIn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPunchIn()
    {
        return $this->hasOne(EmployeePunchLogs::class, ['id' => 'punch_in_id']);
    }

    /**
     * Gets query for [[PunchOut]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPunchOut()
    {
        return $this->hasOne(EmployeePunchLogs::class, ['id' => 'punch_out_id']);
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

    public static function getActiveEmployees()
    {
        $model = EmployeePunchDetails::find()->where(['punch_out_id' => null])->with('lastLog', 'user')->all();

        $result = array_map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->user->name . ' ' . $data->user->last_name,
                'latitude' => $data->lastLog->latitude,
                'longitude' => $data->lastLog->longitude,
                'battery' => $data->lastLog->battery,
                'mobile_network' => $data->lastLog->mobile_network,
                'traveled_km' => $data->total_distance,
                'punch_in_time' => $data->created_at,
                'vehicle_type' => $data->vehicle_type,
                'punch_type' => $data->getPunchTypeLabel()
            ];
        }, $model);

        return $result;
    }

    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['user_id' => 'user_id']);
    }

    public function getVisits()
    {
        return $this->hasMany(Visits::class, ['user_id' => 'user_id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Visits::class, ['user_id' => 'user_id']);
    }

    /**
     * Get punch type options
     * @return array
     */
    public static function getPunchTypeOptions()
    {
        return [
            'S' => 'Sales',
            'O' => 'Office',
        ];
    }

    /**
     * Get punch type label
     * @return string
     */
    public function getPunchTypeLabel()
    {
        $options = self::getPunchTypeOptions();
        return isset($options[$this->punch_type]) ? $options[$this->punch_type] : $this->punch_type;
    }

    /**
     * Search for absent users (users who haven't punched in today)
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function searchAbsentUsers($params)
    {
        $query = Users::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->andWhere(['NOT IN', 'id', 
                EmployeePunchDetails::find()
                    ->select('user_id')
                    ->where(['DATE(created_at)' => date('Y-m-d')])
                    ->andWhere(['is not', 'punch_in_id', null])
            ]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Add any additional filtering here if needed
        $query->andFilterWhere(['like', 'name', $this->name ?? ''])
              ->andFilterWhere(['like', 'email', $this->email ?? '']);

        return $dataProvider;
    }

    /**
     * Search for users who have both punched in and punched out on a specific date
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function searchAttendanceUsers($params)
    {
        $query = Users::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->andWhere(['IN', 'id', 
                EmployeePunchDetails::find()
                    ->select('user_id')
                    ->where(['DATE(created_at)' => date('Y-m-d')])
                    ->andWhere(['is not', 'punch_in_id', null])
                    ->andWhere(['is not', 'punch_out_id', null])
            ]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Add any additional filtering here if needed
        $query->andFilterWhere(['like', 'name', $this->name ?? ''])
              ->andFilterWhere(['like', 'email', $this->email ?? '']);

        return $dataProvider;
    }
}
