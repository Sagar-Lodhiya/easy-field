<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parties;
use yii\data\ArrayDataProvider;

/**
 * PartiesSearch represents the model behind the search form of `app\models\Parties`.
 */
class PartiesSearch extends Parties
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'party_category_id', 'is_active'], 'integer'],
            [['dealer_name', 'dealer_phone', 'firm_name', 'address', 'city_or_town', 'gst_number', 'dealer_aadhar', 'updated_at', 'created_at'], 'safe'],
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
        $query = Parties::find();

        // add conditions that should always apply here

        $query->where(['is_deleted' => 0])->orderBy(['id' => SORT_DESC]);

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
            'employee_id' => $this->employee_id,
            'is_active' => $this->is_active,
            'party_category_id' => $this->party_category_id,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'dealer_name', $this->dealer_name])
            ->andFilterWhere(['like', 'dealer_phone', $this->dealer_phone])
            ->andFilterWhere(['like', 'firm_name', $this->firm_name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city_or_town', $this->city_or_town])
            ->andFilterWhere(['like', 'gst_number', $this->gst_number])
            ->andFilterWhere(['like', 'dealer_aadhar', $this->dealer_aadhar]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function exportSearch($params)
    {
        $query = Parties::find()->orderBy(['firm_name' => SORT_ASC]);

        // add conditions that should always apply here
        $query->where(['is_deleted' => 0]);

        // print_r($dataProvider);
        // exit;

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'is_active' => $this->is_active,
            'party_category_id' => $this->party_category_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'dealer_name', $this->dealer_name])
            ->andFilterWhere(['like', 'dealer_phone', $this->dealer_phone])
            ->andFilterWhere(['like', 'firm_name', $this->firm_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city_or_town', $this->city_or_town])
            ->andFilterWhere(['like', 'gst_number', $this->gst_number])
            ->andFilterWhere(['like', 'dealer_aadhar', $this->dealer_aadhar]);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'pagination' => false,
        ]);


        return $dataProvider;
    }
}
