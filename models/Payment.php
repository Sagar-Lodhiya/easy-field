<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $user_id
 * @property int $party_id
 * @property int $status
 * @property string|null $amount
 * @property string|null $amount_type
 * @property string|null $remark
 * @property string|null $collection_of
 * @property string|null $payments_details
 * @property string|null $amount_details
 * @property string|null $extra
 * @property string|null $payment_of
 * @property string $payments_photo
 * @property int|null $is_deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Parties 
 * @property Users $user
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'party_id', 'payments_photo'], 'required'],
            [['user_id', 'party_id', 'is_deleted','status'], 'integer'],
            [['payment_of', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'string', 'max' => 50],
            [['amount_type', 'remark', 'collection_of', 'payments_details', 'amount_details', 'extra'], 'string', 'max' => 250],
            [['payments_photo'], 'string', 'max' => 255],
            [['party_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parties::class, 'targetAttribute' => ['party_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'party_id' => 'Party ID',
            'amount' => 'Amount',
            'amount_type' => 'Amount Type',
            'remark' => 'Remark',
            'collection_of' => 'Collection Of',
            'payments_details' => 'Payments Details',
            'amount_details' => 'Amount Details',
            'extra' => 'Extra',
            'payment_of' => 'Payment Of',
            'payments_photo' => 'Payments Photo',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Party]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParties()
    {
        return $this->hasOne(Parties::class, ['id' => 'party_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }
}
