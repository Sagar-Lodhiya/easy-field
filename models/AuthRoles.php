<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_roles".
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property AuthPermissions[] $authPermissions
 */
class AuthRoles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 100],
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
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[AuthPermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthPermissions()
    {
        return $this->hasMany(AuthPermissions::class, ['role_id' => 'id']);
    }
    
}
