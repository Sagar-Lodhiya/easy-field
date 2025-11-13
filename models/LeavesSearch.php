<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Leave;

/**
 * LeavesSearch represents the model behind the search form of `app\models\Leave`.
 */
class LeavesSearch extends Leave
{
    public $leave_type;
    public $status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'leave_type_id', 'user_id', 'created_at', 'updated_at','status'], 'integer'],
            [['start_date', 'end_date', 'reason','leave_type'], 'safe'],
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
        $query->joinWith('leaveType');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'leave_type_id' => $this->leave_type_id,
            'leave_type' => $this->leaveType->leave_type ?? '',
            'user_id' => $this->user_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason]);
        $query->andFilterWhere(['like', 'leave_type.leave_type', $this->leave_type]); 

        return $dataProvider;
    }
}
