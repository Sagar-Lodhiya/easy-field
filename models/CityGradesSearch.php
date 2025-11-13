<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CityGrades;

/**
 * CityGradesSearch represents the model behind the search form of `app\models\CityGrades`.
 */
class CityGradesSearch extends CityGrades
{
    public $grade_name; // Virtual attribute for filtering by grade name

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_active', 'is_deleted', 'grade_id', 'state_id'], 'integer'],
            [['city', 'created_at', 'updated_at', 'grade_name'], 'safe'], // ✅ Added grade_name
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = CityGrades::find()->joinWith(['grade']); // ✅ Ensure grade relation is joined

        // Always filter out deleted records
        $query->where(['city_grades.is_deleted' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filtering conditions
        $query->andFilterWhere([
            'city_grades.id' => $this->id,
            'city_grades.grade_id' => $this->grade_id, // ✅ Corrected filtering condition
            'city_grades.is_active' => $this->is_active,
            'city_grades.state_id' => $this->state_id,
            'city_grades.updated_at' => $this->updated_at,
        ]);

        // Filter by city name
        $query->andFilterWhere(['like', 'city_grades.city', $this->city]);

        // Allow filtering by grade name (not ID)
        $query->andFilterWhere(['like', 'grades.name', $this->grade_name])
            ->andFilterWhere(['like', 'city_grades.created_at', $this->created_at]);

        return $dataProvider;
    }
}
