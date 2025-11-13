<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment;

/**
 * PaymentsSearch represents the model behind the search form of `app\models\Payment`.
 */
class PaymentsSearch extends Payment
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'party_id', 'is_deleted', 'status'], 'integer'],
            [['amount', 'amount_type', 'remark', 'collection_of', 'payments_details', 'amount_details', 'extra', 'payment_of', 'payments_photo', 'created_at', 'updated_at'], 'safe'],
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
        $query = Payment::find()->alias('v')->orderBy(['v.created_at' => SORT_DESC]);
    
        // Join with the related table for filtering
        $query->joinWith(['parties p']); // Ensure `parties` relation is defined in the Payment model
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
    
        $this->load($params);
    
        if (!$this->validate()) {
            // If validation fails, return unfiltered data
            return $dataProvider;
        }
    
        // Apply filters for the Payment table
        $query->andFilterWhere(['v.id' => $this->id])
            ->andFilterWhere(['v.user_id' => $this->user_id])
            ->andFilterWhere(['v.party_id' => $this->party_id])
            ->andFilterWhere(['v.is_deleted' => $this->is_deleted])
            ->andFilterWhere(['v.status' => $this->status])
            ->andFilterWhere(['like', 'v.amount', $this->amount])
            ->andFilterWhere(['like', 'v.created_at', $this->created_at])
            ->andFilterWhere(['like', 'v.amount_type', $this->amount_type])
            ->andFilterWhere(['like', 'v.collection_of', $this->collection_of])
            ->andFilterWhere(['like', 'v.payments_details', $this->payments_details])
            ->andFilterWhere(['like', 'v.amount_details', $this->amount_details])
            ->andFilterWhere(['like', 'v.extra', $this->extra]);
    
        // Apply filters for the Parties table
        $query->andFilterWhere(['like', 'p.dealer_name', $this->parties->dealer_name ?? '']);
    
        return $dataProvider;
    }
    

}
