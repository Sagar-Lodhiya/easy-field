<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "states".
 *
 * @property int $id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property CityGrades[] $cityGrades
 */
class States extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'states';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
            'is_active' => 'Status',
        ];
    }

    /**
     * Gets query for [[CityGrades]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCityGrades()
    {
        return $this->hasMany(CityGrades::class, ['state_id' => 'id']);
    }

    public static function getStatesList()
    {
        $model = States::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
}
