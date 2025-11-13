<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EmployeePunchDetails;
use Yii;
use yii\data\ArrayDataProvider;

/**
 * EmployeePunchDetailsSearch represents the model behind the search form of `app\models\EmployeePunchDetails`.
 */
class EmployeePunchDetailsSearch extends EmployeePunchDetails
{

    public $start_date;
    public $end_date;
    public $date_time_range; // Add this line

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'punch_in_id', 'punch_out_id', 'last_log_id'], 'integer'],
            [['punch_in_place', 'punch_in_meter_reading_in_km', 'punch_in_image', 'punch_out_place', 'punch_out_meter_reading_in_km', 'punch_out_image', 'vehicle_type', 'tour_details', 'partner_name', 'created_at', 'updated_at'], 'safe'],
            [['traveled_km', 'total_distance', 'da', 'ta'], 'number'],
            [['start_date', 'end_date', 'date_time_range'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */


    public function exportSearch($params)
    {
        $query = EmployeePunchDetails::find()
            ->alias('epd') // Alias for clarity
            ->joinWith(['expenses e', 'visits v', 'user u']) // Join related tables
            ->orderBy(['epd.id' => SORT_DESC]);

        $this->load($params);

        if (!$this->validate()) {
            return new ArrayDataProvider(['allModels' => []]);
        }
        
        // Filter by EmployeePunchDetails created_at
        if (!empty($this->date_time_range)) {
            list($start, $end) = explode(' - ', $this->date_time_range);
            $query->andFilterWhere(['between', 'Date(epd.created_at)', date('Y-m-d', strtotime($start)),date('Y-m-d', strtotime($end))]);
        }

        // Filter by Expenses created_at (matching EmployeePunchDetails)
        // $query->andFilterWhere(['between', 'e.created_at', $this->start_date, $this->end_date]);

        // Filter by Visits created_at (matching EmployeePunchDetails)
        // $query->andFilterWhere(['between', 'v.created_at', $this->start_date, $this->end_date]);

        // Additional filters for Users, DA, TA, etc.
        $query->andFilterWhere(['like', 'u.name', $this->user_id])
            ->andFilterWhere(['like', 'epd.vehicle_type', $this->vehicle_type])
            ->andFilterWhere(['>=', 'epd.da', $this->da])
            ->andFilterWhere(['>=', 'epd.ta', $this->ta])
            ->andFilterWhere(['like', 'epd.total_distance', $this->total_distance])
            ->andFilterWhere(['like', 'epd.last_log_id', $this->last_log_id]);


        $data = $query->all();

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => false,
        ]);
    }


    public function search($params)
    {
        $query = EmployeePunchDetails::find()
            ->alias('epd') // Alias for clarity
            ->joinWith(['expenses e', 'visits v', 'user u']) // Join related tables
            ->orderBy(['epd.id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false, // Disable pagination
        ]);

        
        
        
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        // Filter by EmployeePunchDetails created_at
        if (!empty($this->date_time_range)) {
            list($start, $end) = explode(' - ', $this->date_time_range);
            // print_r($start);exit;
            $query->andFilterWhere(['between', 'Date(epd.created_at)', date('Y-m-d', strtotime($start)),date('Y-m-d', strtotime($end))]);
        }

        // Filter by Expenses created_at (matching EmployeePunchDetails)
        // $query->andFilterWhere(['between', 'e.created_at', $this->start_date, $this->end_date]);

        // Filter by Visits created_at (matching EmployeePunchDetails)
        // $query->andFilterWhere(['between', 'v.created_at', $this->start_date, $this->end_date]);

        // Additional filters for Users, DA, TA, etc.
        $query->andFilterWhere(['u.id' => $this->user_id])
            ->andFilterWhere(['like', 'epd.vehicle_type', $this->vehicle_type])
            ->andFilterWhere(['like', 'epd.traveled_km', $this->traveled_km])
            ->andFilterWhere(['like', 'epd.created_at', $this->created_at])
            ->andFilterWhere(['>=', 'epd.da', $this->da])
            ->andFilterWhere(['>=', 'epd.ta', $this->ta]);

        return $dataProvider;
    }
}
