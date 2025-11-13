<?php

namespace app\modules\api\models;

use app\models\Users;
use app\modules\api\helpers\ApplicationHelper;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *  
 */
class LoginForm extends Model
{
    public $username;
    public $device_id;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'device_id'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['device_id', 'validateDeviceId'],
        ];
    }

    /**
     * Validates the device_id.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateDeviceId($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if($user) {
                if($user->device_id && !$user->validateDeviceId($this->device_id)){
                    $this->addError($attribute, 'Invalid credentials.');
                } elseif(!$user->device_id){
                    $user->setUserDevice($user->id, $this->device_id);
                }
            } else {
                $this->addError($attribute, 'Invalid credentials.');
            }

        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->updateUser($this->getUser());
            return true;
        } 
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
    public function updateUser($user)
    {   
        $model = Users::findOne($user->id);
        $request = Yii::$app->request->bodyParams;
        if (isset($request['device_id']) && $request['device_id'] != "") {
            $model->device_id = $request['device_id'];
        }
        if (isset($request['device_type']) && $request['device_type'] != "") {
            $model->device_type = $request['device_type'];
        }
        if (isset($request['device_model']) && $request['device_model'] != "") {
            $model->device_model = $request['device_model'];
        }
        if (isset($request['app_version']) && $request['app_version'] != "") {
            $model->app_version = $request['app_version'];
        }
        if (isset($request['os_version']) && $request['os_version'] != "") {
            $model->os_version = $request['os_version'];
        }
        if (isset($request['device_token']) && $request['device_token'] != "") {
            $model->device_token = $request['device_token'];
        }
        $model->access_token =  Yii::$app->security->generateRandomString();

        if($model->save()){
            $this->_user = $model;
            return true;
        } else{
            print_r($model->getErrors());
        }

    }
}
