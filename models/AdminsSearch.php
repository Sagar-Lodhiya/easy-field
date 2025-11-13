<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Admins;
use Yii;

/**
 * AdminsSearch represents the model behind the search form of `app\models\Admins`.
 */
class AdminsSearch extends Admins
{
    public $is_super_admin; // Add this field
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'is_deleted','parent_id','is_super_admin'], 'integer'],
            [['name', 'email', 'type', 'password', 'created_at', 'updated_at'], 'safe'],
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
    $admin = Admins::findOne(Yii::$app->user->identity->id);
    $id = array_column($admin->descendants, 'id');

    // Initialize the query with a filter to exclude deleted admins
    $query = Admins::find()->where(['is_deleted' => 0]);

    // Apply additional filtering based on the current user's parent_id
    if (Yii::$app->user->identity->parent_id) {
        $query->andWhere(['in', 'parent_id', $id]);
    } else {
        // If parent_id is not set, just keep the query as is
    }

    // Ensure that the current admin is also included
    $query->orWhere(['id' => $admin->id]);

    // Add pagination
    $pageSize = (isset($params['per-page']) && $params['per-page'] !== 0) ? $params['per-page'] : 10;

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
            'pageSize' => $pageSize,
        ],
    ]);

    $this->load($params);

    // Validate the loaded parameters
    if (!$this->validate()) {
        // Optionally, you can uncomment the line below if you want no results on validation failure
        // $query->where('0=1');
        return $dataProvider;
    }

    // Apply filtering conditions based on model attributes
    $query->andFilterWhere([
        'id' => $this->id,
        'is_active' => $this->is_active,
        'parent_id' => $this->parent_id,    
        'type' => $this->type,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'is_super_admin' => $this->is_super_admin,
    ]);

    // Apply LIKE filters for text fields
    $query->andFilterWhere(['like', 'name', $this->name])
          ->andFilterWhere(['like', 'email', $this->email])
          ->andFilterWhere(['like', 'password', $this->password]);

    return $dataProvider;
}

}
