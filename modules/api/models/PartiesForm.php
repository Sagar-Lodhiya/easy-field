<?php
namespace app\modules\api\models;

use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\api\helpers\ImageUploadHelper;
use app\models\Parties;
use app\models\Users;
use app\models\PartyCategories;
use Yii;

class PartiesForm extends Model{
public $id;
public $party_category_id;
public $firm_name;
public $dealer_phone;
public $city_or_town;
public $dealer_name;
public $employee_id;
public $gst_number;
public $dealer_aadhar;
public $address;
public $created_at;
public function rules(){
    return[
        [['party_category_id', 'dealer_name', 'dealer_phone', 'firm_name', 'city_or_town','employee_id','gst_number','dealer_aadhar', 'address'], 'required'],
        [[ 'party_category_id'], 'integer'],
        [['created_at'], 'safe'],
        [['dealer_name', 'dealer_phone', 'firm_name', 'city_or_town'], 'string', 'max' => 150],
        [['party_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => PartyCategories::class, 'targetAttribute' => ['party_category_id' => 'id']],
    ];
}


public function saveParties(){
    if($this->validate()){
        $party = new Parties();
        $party->attributes = $this->attributes;
        $party->created_at = date('Y-m-d H:i:s');


       
        $CategoryName = $party->partyCategory->name;

        if ($party->save()) {
            return [
              'party_category' => $party->party_category_id,
              'dealer_name' => $CategoryName,
              'firm_name' => $party->firm_name,
              'dealer_phone' =>$party->dealer_phone,
              'city_or_town' => $party->city_or_town,
              'employee_id' => $party->employee_id,
              'gst_number' => $party->gst_number,
              'dealer_aadhar' => $party->dealer_aadhar,
            ]; 
        }
        $this->addErrors($party->errors);
    }
    return false;
}

public static function listParties($id)
{
    return Parties::find()->where(['id' => $id])->all();
}


}