<?php

namespace app\models;
use yii\base\Model;
use app\models\EmployeePunchDetails;
use yii\data\ActiveDataProvider;



/**
 * AttendanceSearch represents the search model for filtering active and absent users.
 */
class AttendanceSearch extends EmployeePunchDetails
{
    public $name;
    public $created_at;
    public $traveled_km;
    public $punch_type;
    public $email;
    public $phone_no;
    public $date_time_range;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['punch_in_time', 'punch_out_time', 'vehicle_type', 'punch_type', 'punch_in_date','created_at','traveled_km','name','email','phone_no','date_time_range'], 'safe'],

        ];
    }
     /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Fetch active users (punched in today).
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchActiveUsers($params)
    {
        $query = EmployeePunchDetails::find()
            ->distinct()
            ->where(['DATE(employee_punch_details.created_at)' => date('Y-m-d')]) // Filter by today's date
            ->andWhere(['punch_out_id' => null])
            ->joinWith('user');

        $query->orderBy(['id' => SORT_DESC]); // Optional sorting
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // Apply filters
        $query->andFilterWhere([
            'id' => $this->id,
            // 'created_at' => $this->created_at,
            'traveled_km' => $this->traveled_km,
            'vehicle_type' => $this->vehicle_type,
            'punch_type' => $this->punch_type

        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
         ->andFilterWhere(['like', 'employee_punch_details.created_at', $this->created_at])
        ->andFilterWhere(['like', 'email', $this->email]);



        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }


    /**
     * Fetch absent users (not punched in today).
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchAbsentUsers($params)
    {
        $query = Users::find();
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');

        // Fetch IDs of users who punched in today
        $activeUserIds = \app\models\EmployeePunchDetails::find()
            ->select('user_id')
            ->distinct()
            ->where(['between', 'created_at', $todayStart, $todayEnd])
            ->column();

        $query->andWhere(['not in', 'id', $activeUserIds]);
        $query->orderBy(['id' => SORT_DESC]); // Latest records first

        // Apply filters
        $this->load($params);
        if (!$this->validate()) {
            return new ActiveDataProvider(['query' => $query]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'email', $this->email]);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function search($params)
    {
        $query = Users::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    /**
     * Search for users who have both punched in and punched out on a specific date
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function searchAttendanceUsers($params)
    {
        $query = EmployeePunchDetails::find()
            ->where(['DATE(employee_punch_details.created_at)' => date('Y-m-d')])
            ->andWhere(['is not', 'punch_in_id', null])
            ->andWhere(['is not', 'punch_out_id', null])
            ->joinWith('user')
            ->andWhere(['users.is_active' => 1, 'users.is_deleted' => 0]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Add any additional filtering here if needed
        $query->andFilterWhere(['like', 'users.name', $this->name ?? ''])
              ->andFilterWhere(['like', 'users.email', $this->email ?? '']);

        return $dataProvider;
    }
}