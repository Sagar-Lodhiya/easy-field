<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_permissions".
 *
 * @property int $id
 * @property int $role_id
 * @property int $auth_items_id
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property AuthItems $authItems
 * @property AuthRoles $role
 */
class AuthPermissions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'auth_items_id'], 'required'],
            [['role_id', 'auth_items_id', 'is_active', 'is_deleted'], 'integer'],
            [['auth_items_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItems::class, 'targetAttribute' => ['auth_items_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRoles::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'auth_items_id' => 'Auth Items ID',
            'is_active' => 'Is Active',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[AuthItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasOne(AuthItems::class, ['id' => 'auth_items_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(AuthRoles::class, ['id' => 'role_id']);
    }
}
