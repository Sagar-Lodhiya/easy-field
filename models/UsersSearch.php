<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'is_active', 'is_deleted','office_punchin_enabled'], 'integer'],
            [['name', 'email', 'device_id', 'device_type', 'device_model', 'app_version', 'os_version', 'device_token', 'created_at', 'updated_at', 'user_type'], 'safe'],
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
        $query = Users::find();

        // add conditions that should always apply here
        $query->where(['is_deleted' => 0]);

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
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
            'office_punchin_enabled' => $this->office_punchin_enabled,
            'user_type' => $this->user_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone_no', $this->phone_no])
            ->andFilterWhere(['like', 'device_id', $this->device_id])
            ->andFilterWhere(['like', 'device_type', $this->device_type])
            ->andFilterWhere(['like', 'device_model', $this->device_model])
            ->andFilterWhere(['like', 'app_version', $this->app_version])
            ->andFilterWhere(['like', 'os_version', $this->os_version])
            ->andFilterWhere(['like', 'device_token', $this->device_token]);

        return $dataProvider;
    }
}
