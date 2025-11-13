<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "parties".
 *
 * @property int $id
 * @property int $employee_id
 * @property int|null $party_category_id
 * @property string|null $dealer_name
 * @property string|null $dealer_phone
 * @property string|null $firm_name
 * @property string|null $address
 * @property string|null $city_or_town
 * @property string|null $gst_number
 * @property string|null $dealer_aadhar
 * @property int $is_deleted
 * @property int $is_active
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Users $employee
 * @property PartyCategories $partyCategory
 */
class Parties extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parties';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['employee_id', 'party_category_id', 'dealer_name', 'dealer_phone', 'firm_name', 'city_or_town', 'gst_number', 'dealer_aadhar'], 'required'],
            [['employee_id', 'party_category_id', 'is_active', 'is_deleted'], 'integer'],
            [['address'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['dealer_name', 'dealer_phone', 'firm_name', 'city_or_town', 'gst_number', 'dealer_aadhar'], 'string', 'max' => 150],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['party_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PartyCategories::class, 'targetAttribute' => ['party_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'User Name',
            'party_category_id' => 'Party Category',
            'dealer_name' => 'Dealer Name',
            'dealer_phone' => 'Dealer Phone',
            'firm_name' => 'Firm Name',
            'address' => 'Address',
            'is_active' => 'Status',
            'city_or_town' => 'City Or Town',
            'gst_number' => 'Gst Number',
            'dealer_aadhar' => 'Dealer Aadhar',
            'updated_at' => 'Updated At',
            'created_at' => 'Date',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Users::class, ['id' => 'employee_id']);
    }

    /**
     * Gets query for [[PartyCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartyCategory()
    {
        return $this->hasOne(PartyCategories::class, ['id' => 'party_category_id']);
    }

    public static function getPArtiesList()
    {
        $model = Parties::find()
            ->where(['is_active' => 1, 'is_deleted' => 0])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
}
