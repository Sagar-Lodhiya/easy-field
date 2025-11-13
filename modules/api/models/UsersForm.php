<?php
namespace app\modules\api\models;

use yii\base\Model;
use app\models\Users;
use Yii;

class UsersForm extends Model
{
    public $name;      // Name of the user to be updated
    public $profile;   // Profile of the user to be updated

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],  // Name is required
            [['profile'], 'string'], // Profile is optional but must be a string
        ];
    }

    /**
     * Save the user details based on the provided access token.
     *
     * @param string $access_token
     * @return array|bool Updated user data or false if failed
     */
    public function saveUsers($access_token)
    {
        // Validate the model first
        if ($this->validate()) {
            // Find the user based on the access token
            $user = Users::find()->where(['access_token' => $access_token])->one();
            
            if (!$user) {
                // If no user is found, add an error and return false
                $this->addError('access_token', 'User not found.');
                return false;
            }
          
            // Update the user's name and profile
            $user->name = $this->name;
            $user->profile = $this->profile;

            // Save the updated user information to the database
            if ($user->save()) {
                return [
                    'name' => $user->name,
                    'profile' => $user->profile,
                ];
            } else {
                // Add any validation errors from the user model to the form model
                $this->addErrors($user->errors);
            }
        }
        
        // If validation fails or save fails, return false
        return false;
    }
}

