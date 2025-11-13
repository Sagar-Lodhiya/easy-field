<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_items".
 *
 * @property int $id
 * @property int $module_id
 * @property string $name
 * @property string $url
 * @property int $sort_order
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property AuthPermissions[] $authPermissions
 * @property AuthModules $module
 */
class AuthItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['module_id', 'name', 'url', 'sort_order'], 'required'],
            [['module_id', 'sort_order', 'is_active', 'is_deleted'], 'integer'],
            [['name', 'url'], 'string', 'max' => 100],
            [['module_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthModules::class, 'targetAttribute' => ['module_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module_id' => 'Module ID',
            'name' => 'Name',
            'url' => 'Url',
            'sort_order' => 'Sort Order',
            'is_active' => 'Is Active',
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
        return $this->hasMany(AuthPermissions::class, ['auth_items_id' => 'id']);
    }

    /**
     * Gets query for [[Module]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModule()
    {
        return $this->hasOne(AuthModules::class, ['id' => 'module_id']);
    }

    /**
     * Gets query for [[AuthModules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthModules()
    {
        return $this->hasOne(AuthModules::class, ['id' => 'module_id']);
    }
}
