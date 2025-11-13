<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city_grades".
 *
 * @property int $id
 * @property string $city
 * @property int $grade_id
 * @property int $state_id
 * @property int $is_active
 * @property int $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Grades $grade
 */
class CityGrades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city_grades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city', 'grade_id', 'state_id'], 'required'],
            [['grade_id', 'state_id', 'is_active', 'is_deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['city'], 'string', 'max' => 150],

            // Ensure grade_id exists in Grades table
            [['grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Grades::class, 'targetAttribute' => ['grade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'City',
            'grade_id' => 'Grade',
            'grade.name' => 'Grade',
            'state_id' => 'State',
            'state.name' => 'State',
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Grade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGrade()
    {
        return $this->hasOne(Grades::class, ['id' => 'grade_id']);
    }

    /**
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(States::class, ['id' => 'state_id']);
    }

    /**
     * Returns a list of active cities.
     *
     * @return array
     */
    public static function getGradeList()
    {
        return ArrayHelper::map(Grades::find()->all(), 'id', 'name');
    }

    public static function getCityGradeList(){
        return ArrayHelper::map(CityGrades::find()->all(), 'id', 'city');
    }
    
}
