<?php

namespace app\modules\api\models;

use app\models\LeaveType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Leave;
use Yii;

/**
 * LeaveSearch represents the model behind the search form of `app\models\Leave`.
 */
class LeavesSearch extends Leave
{
    public $leave_type;
    public $status;
    public $year;
    public $month;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'leave_type_id', 'user_id', 'created_at', 'updated_at','status'], 'integer'],
            [['start_date', 'end_date', 'reason','leave_type', 'month', 'year'], 'safe'],
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
    public function search($params)
    {
        $query = Leave::find();
        // add conditions that should always apply here
        $query->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC]);

        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        
        if($this->month !== '' && $this->year !== '' ){
            $dt = date("F", mktime(0, 0, 0, $this->month, 10)) . ' ' . $this->year;
            $firstDay = date("Y-m-01", strtotime($dt));
            $lastDay = date("Y-m-t", strtotime($dt));
            $query->andWhere(['<=', 'start_date', $lastDay])
            ->andWhere(['>=', 'end_date', $firstDay]);
            
        } 
        
        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            'leave_type_id' => $this->leave_type_id,
            // 'leave_type' => $this->leaveType->leave_type ?? '',
        ]);

        // $query->andFilterWhere(['like', 'reason', $this->reason]);
        // $query->andFilterWhere(['like', 'leave_type.leave_type', $this->leave_type]); 

        return $dataProvider;
    }
}
