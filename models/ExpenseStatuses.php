<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "expense_statuses".
 *
 * @property int $id
 * @property string $name
 *
 * @property Expenses[] $expenses
 */
class ExpenseStatuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expense_statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expenses::class, ['status_id' => 'id']);
    }

    public static function getExpenseStatusList()
    {
        $model = ExpenseStatuses::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
}
