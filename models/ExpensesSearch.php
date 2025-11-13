<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Expenses;

/**
 * ExpensesSearch represents the model behind the search form of `app\models\Expenses`.
 */
class ExpensesSearch extends Expenses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'city_id', 'user_id', 'category_id', 'is_night_stay', 'admin_id', 'status_id'], 'integer'],
            [['requested_amount', 'approved_amount'], 'number'],
            [['remark', 'expense_photo', 'expense_details', 'expense_type', 'created_at', 'updated_at', 'expense_date'], 'safe'],
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
    public function searchFixedExpense($params)
    {
        $query = Expenses::find();

        // add conditions that should always apply here
        $query->where(['expense_type' => 'fixed'])->orderBy(['id' => SORT_DESC]);

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
            'city_id' => $this->city_id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'requested_amount' => $this->requested_amount,
            'approved_amount' => $this->approved_amount,
            'is_night_stay' => $this->is_night_stay,
            'admin_id' => $this->admin_id,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expense_date' => $this->expense_date,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'expense_photo', $this->expense_photo])
            ->andFilterWhere(['like', 'expense_details', $this->expense_details])
            ->andFilterWhere(['like', 'expense_type', $this->expense_type]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchClaimedExpense($params)
    {
        $query = Expenses::find();

        // add conditions that should always apply here
        $query->where(['expense_type' => 'claimed'])->orderBy(['id' => SORT_DESC]);

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
            'city_id' => $this->city_id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'requested_amount' => $this->requested_amount,
            'approved_amount' => $this->approved_amount,
            'is_night_stay' => $this->is_night_stay,
            'admin_id' => $this->admin_id,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'expense_date' => $this->expense_date,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'expense_photo', $this->expense_photo])
            ->andFilterWhere(['like', 'expense_details', $this->expense_details])
            ->andFilterWhere(['like', 'expense_type', $this->expense_type]);

        return $dataProvider;
    }
}
