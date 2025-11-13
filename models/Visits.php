<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visits".
 *
 * @property int $id
 * @property int $user_id
 * @property int $party_id
 * @property string|null $duration
 * @property string|null $discussion_point
 * @property string|null $remark
 * @property string|null $place
 * @property string|null $time
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Parties $party
 * @property Users $user
 */
class Visits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'party_id'], 'required'],
            [['user_id', 'party_id', 'is_deleted'], 'integer'],
            [['time', 'created_at', 'updated_at'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['duration'], 'string', 'max' => 50],
            [['discussion_point', 'remark', 'place'], 'string', 'max' => 250],
            [['party_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parties::class, 'targetAttribute' => ['party_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'party_id' => 'Party ID',
            'duration' => 'Duration',
            'discussion_point' => 'Discussion Point',
            'remark' => 'Remark',
            'place' => 'Place',
            'time' => 'Time',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Party]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParty()
    {
        return $this->hasOne(Parties::class, ['id' => 'party_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
