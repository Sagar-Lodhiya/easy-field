<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth_modules".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $sort_order
 * @property int $is_active
 * @property int $is_deleted
 *
 * @property AuthItems[] $authItems
 */
class AuthModules extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_modules';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'sort_order'], 'required'],
            [['sort_order', 'is_active', 'is_deleted'], 'integer'],
            [['name', 'url'], 'string', 'max' => 100],
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
            'url' => 'Url',
            'sort_order' => 'Sort Order',
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
        return $this->hasMany(AuthItems::class, ['module_id' => 'id']);
    }

}
