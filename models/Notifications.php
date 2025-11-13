<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $user_id
 * @property string $text
 * @property int $admin_id
 * @property int $is_read
 * @property int $is_cleared
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Admins $admin
 * @property Users $user
 */
class Notifications extends \yii\db\ActiveRecord
{
    public $adminn;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'text', 'admin_id'], 'required'],
            [['user_id', 'admin_id', 'is_read', 'is_cleared'], 'integer'],
            [['created_at', 'updated_at','adminn'], 'safe'],
            [['text'], 'string', 'max' => 255],
            [['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['admin_id' => 'id']],
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
            'text' => 'Text',
            'admin_id' => 'Admin ID',
            'is_read' => 'Is Read',
            'is_cleared' => 'Is Cleared',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Admin]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdmin()
    {
        return $this->hasOne(Admins::class, ['id' => 'admin_id']);
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
