<?php

namespace app\modules\api\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Parties;
use Yii;

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
            [['id', 'employee_id', 'party_category_id', 'is_active', 'is_deleted'], 'integer'],
            [['dealer_name', 'dealer_phone', 'firm_name', 'address', 'city_or_town', 'gst_number', 'dealer_aadhar', 'created_at', 'updated_at'], 'safe'],
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
        $query = Parties::find()->where(['employee_id' => Yii::$app->user->identity->id]);

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
            'employee_id' => $this->employee_id,
            'party_category_id' => $this->party_category_id,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'dealer_name', $this->dealer_name])
            ->andFilterWhere(['like', 'dealer_phone', $this->dealer_phone])
            ->andFilterWhere(['like', 'firm_name', $this->firm_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city_or_town', $this->city_or_town])
            ->andFilterWhere(['like', 'gst_number', $this->gst_number])
            ->andFilterWhere(['like', 'dealer_aadhar', $this->dealer_aadhar]);

        return $dataProvider;
    }
}
