<?php
namespace app\modules\api\models;


use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\api\helpers\ImageUploadHelper;
use app\models\Payment;
use Yii;

class PaymentsForm extends Model
{
    public $created_at;
    public $party_id; // Automatically set to logged-in user
    public $amount;
    public $amount_type;
    public $amount_details;
    public $payments_photo;
    public $collection_of;
    public $payments_details;
    public $extra;
    public $user_id;
    public $party;

    public function rules()
    {
        return [
            [['party_id'], 'required'],
            [['party_id'], 'integer'],
            [['payment_of', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'string', 'max' => 50],
            [['amount_type', 'collection_of', 'payments_details', 'amount_details', 'extra'], 'string', 'max' => 250],
            [['payments_photo'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
            

        ];
    }

    public function savePayments()
    {

        if ($this->validate()) {

            $payments = new Payment();
            $payments->attributes = $this->attributes;

            // Set user_id to the logged-in user ID
            $payments->user_id = Yii::$app->user->id;  // Logged-in user

            // Access the related Party model through the correct relation method
            $partyName = $payments->parties ? $payments->parties->dealer_name : '';  // Correct relation access

            // Handle payments photo upload using ImageUploadHelper
            $imageFile = UploadedFile::getInstanceByName('payments_photo');
            $validation = ImageUploadHelper::validateImage($imageFile);

            if (!$validation) {
                $this->addError('payments_photo', 'Invalid File image');
                return false;
            }

            $payments->payments_photo = ImageUploadHelper::uploadImage($imageFile);

            if ($payments->save()) {
                return [
                    // 'created_at' => $payments->created_at,
                    'party_id' => $payments->party_id,
                    'party_name' => $partyName,
                    'amount' => $payments->amount,
                    'amount_type' => $payments->amount_type,
                    'amount_details' => $payments->amount_details,
                    'collection_of' => $payments->collection_of,
                    'payments_details' => $payments->payments_details,
                    'extra' => $payments->extra,
                    'payments_photo' => $payments->payments_photo,
                ];
            } else {

                Yii::error($payments->errors, 'payments_save_error');
                echo json_encode($payments->errors);
                exit;


            }
        }

        return false;
    }



}
