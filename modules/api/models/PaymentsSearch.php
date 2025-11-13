<?php

namespace app\modules\api\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment;
use Yii;

/**
 * PaymentsSearch represents the model behind the search form of `app\models\Payment`.
 */
class PaymentsSearch extends Payment
{
    public $dealer_name;
  
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'party_id', 'is_deleted', 'status'], 'integer'],
            [['amount', 'amount_type', 'remark', 'collection_of', 'payments_details', 'amount_details', 'extra', 'payment_of', 'payments_photo', 'created_at', 'updated_at','dealer_name'], 'safe'],
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
        $query = Payment::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['created_at' => SORT_DESC]);
        $query->joinWith('parties'); // Join with the related party table

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Explicitly specify the table name or alias for the `created_at` column
        $query->andFilterWhere([
            'payments.id' => $this->id,
            // 'payments.user_id' => $this->user_id,
            'payments.party_id' => $this->party_id,
            'payments.is_deleted' => $this->is_deleted,
            'payments.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'payments.amount', $this->amount])
            ->andFilterWhere(['like', 'payments.amount_type', $this->amount_type])
            ->andFilterWhere(['like', 'payments.remark', $this->remark])
            ->andFilterWhere(['like', 'payments.collection_of', $this->collection_of])
            ->andFilterWhere(['like', 'payments.payments_details', $this->payments_details])
            ->andFilterWhere(['like', 'payments.amount_details', $this->amount_details])
            ->andFilterWhere(['like', 'payments.extra', $this->extra])
            ->andFilterWhere(['like', 'payments.payment_of', $this->payment_of])
            ->andFilterWhere(['like', 'payments.payments_photo', $this->payments_photo])
            ->andFilterWhere(['like', 'payments.created_at', $this->created_at])
            ->andFilterWhere(['like', 'payments.updated_at', $this->updated_at]);

        // If filtering by `parties` table, use the alias
        $query->andFilterWhere(['like', 'parties.dealer_name', $this->dealer_name]);

        return $dataProvider;
    }


}
