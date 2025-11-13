<?php
namespace app\modules\api\models;

use app\models\Parties;
use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\api\helpers\ImageUploadHelper;
use app\models\Visits;
use app\models\Users;
use Yii;    

class VisitsForm extends Model{

    public $user_id; // Automatically set to logged-in user
    public $party_id;
    public $discussion_point;
    public $duration;
    public $remark;
    public $created_at;
    public $place;
   public $latitude;
   public $longitude;
   public $time;




    public function rules(){
        return [
            [['user_id', 'party_id','remark','place','duration','discussion_point','latitude','longitude'], 'required'],
            [['user_id', 'party_id' ], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['time', 'created_at', 'updated_at'], 'safe'],
            [['duration'], 'string', 'max' => 50],
            [['discussion_point', 'remark', 'place'], 'string', 'max' => 250],
            [['party_id'], 'exist', 'skipOnError' => true, 'targetClass' => Parties::class, 'targetAttribute' => ['party_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    

    public function saveVisits(){
        if($this->validate()){
            
            $visit = new Visits();
            $visit->attributes = $this->attributes;


            $visit->user_id = Yii::$app->user->id;
            $partyName = $visit->party->dealer_name;


            if ($visit->save()) {
                return [
                  
                    'created_at' => $visit->created_at,
                    'time' => $visit->time,
                    'place' => $visit->place,
                    'party_id' => $visit->party_id,
                    'party_name' => $partyName,
                    'duration' => $visit->duration,
                    'discussion_point' => $visit->discussion_point,
                    'remarks' => $visit->remark,
                ]; 
            }
            $this->addErrors($visit->errors);
            
        }
        return false;
    }

    public static function listVisits($userId)
    {
        return Visits::find()->where(['user_id' => $userId])->all();
    }
}
