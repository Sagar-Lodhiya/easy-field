<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cms".
 *
 * @property int $id
 * @property string $page
 * @property string $title
 * @property string $description
 */
class Cms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page', 'title', 'description'], 'required'],
            [['description'], 'string'],
            [['page', 'title'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
            'title' => 'Title',
            'description' => 'Description',
        ];
    }
}
