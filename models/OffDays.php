<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "off_days".
 *
 * @property int $id
 * @property string $title
 * @property int $type The type of off day: 1 = Weekly, 2 = Monthly, 3 = Yearly, 4 = Once-off
 * @property string $date
 * @property int $is_active
 * @property int $is_deleted
 * @property string $updated_at
 * @property string $created_at
 */
class OffDays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'off_days';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'date'], 'required'],
            [['type'], 'integer'],
            [['date', 'updated_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'type' => 'Type',
            'date' => 'Date',
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    public static function getTypeOptions()
    {
        return [
            1 => 'Weekly',
            2 => 'Monthly',
            3 => 'Yearly',
            4 => 'Once-off',
        ];
    }
}
