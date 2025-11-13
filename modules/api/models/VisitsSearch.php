<?php

namespace app\modules\api\models;

use app\models\Parties;
use app\models\Users;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Visits;

/**
 * VisitsSearch represents the model behind the search form of `app\models\Visits`.
 */
class VisitsSearch extends Visits
{

    public $dealer_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'party_id', 'is_deleted'], 'integer'],
            [['duration', 'discussion_point', 'remark', 'place', 'time', 'created_at', 'updated_at'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['dealer_name'], 'safe'],
            [['party_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parties::class, 'targetAttribute' => ['party_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],

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
    $query = Visits::find()->alias('v')->where(['user_id' => Yii::$app->user->identity->id])->orderBy(['v.created_at' => SORT_DESC]);

    // Join with the `parties` table
    $query->joinWith(['party p']);

    // Create the data provider
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    // Load and validate the filter parameters
    $this->load($params);

    if (!$this->validate()) {
        Yii::error('Validation errors: ' . json_encode($this->errors));
        return $dataProvider;
    }

    // Apply filtering conditions
    $query->andFilterWhere([
        'v.id' => $this->id,
        'v.user_id' => $this->user_id,
        'v.party_id' => $this->party_id,
        'v.time' => $this->time,
        'v.latitude' => $this->latitude,
        'v.longitude' => $this->longitude,
        'v.is_deleted' => $this->is_deleted,
        
        'v.updated_at' => $this->updated_at,
    ]);

    $query->andFilterWhere(['like', 'v.duration', $this->duration])
        ->andFilterWhere(['like', 'v.discussion_point', $this->discussion_point])
        ->andFilterWhere(['like', 'v.remark', $this->remark])
        ->andFilterWhere(['like', 'v.created_at', $this->created_at])
        ->andFilterWhere(['like', 'v.place', $this->place])
        ->andFilterWhere(['like', 'p.dealer_name', $this->dealer_name]); // Use the alias `p`

    return $dataProvider;
}


}
