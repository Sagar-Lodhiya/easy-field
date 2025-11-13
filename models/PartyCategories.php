<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "party_categories".
 *
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property int $is_deleted
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class  PartyCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'party_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_active', 'is_deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getPartyCategoriesList()
    {
        $model = PartyCategories::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
}
