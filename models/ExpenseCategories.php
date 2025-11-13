<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "expense_categories".
 *
 * @property int $id
 * @property string|null $name
 * @property string $type Type of the record
 * @property int $is_active
 * @property int $is_deleted
 * @property string $created_at
 * @property string $updated_at
 */
class ExpenseCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string'],
            [['is_active', 'is_deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
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
            'type' => 'Type',
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getExpenseCategoriesList()
    {
        $model = ExpenseCategories::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->andWhere(['id' => [1, 2]])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }

    public static function getTypeList()
    {
        $model = ExpenseCategories::find()
            ->select(['type']) // Select only the 'type' column
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->distinct() // Ensure unique values
            ->orderBy(['type' => SORT_ASC])
            ->asArray() // Fetch as array for mapping
            ->all();

        $list = ArrayHelper::map($model, 'type', 'type'); // Use 'type' as both key and value
        return $list;
    }
}
