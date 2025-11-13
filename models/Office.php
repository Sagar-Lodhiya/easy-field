<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "office".
 *
 * @property int $id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property int $is_active
 * @property int $is_deleted
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Office extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active'], 'default', 'value' => 1],
            [['is_deleted'], 'default', 'value' => 0],
            [['name', 'latitude', 'longitude'], 'required'],
            [['is_active', 'is_deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'latitude', 'longitude'], 'string', 'max' => 255],
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
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
