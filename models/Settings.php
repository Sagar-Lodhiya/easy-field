<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $type
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    public $is_force_update;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'value'], 'required'],
            [['type', 'value'], 'string', 'max' => 255],
            [['is_force_update'], 'integer', 'min' => 0, 'max' => 1],
         
        ];
    }

    /**
     * {@inheritdoc}    
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
        ];
    }
}
