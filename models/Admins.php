<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admins".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int|null $parent_id
 * @property string $password_hash
 * @property string|null $access_token
 * @property int $is_active
 * @property int $is_deleted
 * @property int $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Admins[] $childrens
 * @property Admins[] $descendants
 * @property Admins $parent
 */
class Admins extends \yii\db\ActiveRecord
{
    public $password_hash;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admins';
    }

    public static function getTypeOptions()
    {


        $currentUserType = Yii::$app->user->identity->type;

        if ($currentUserType == 1) {
            return [
                2 => 'Admin',
                3 => 'Sub Admin',
            ];
        } elseif ($currentUserType == 2) {
            return [
                3 => 'Sub Admin',
            ];
        } elseif ($currentUserType == 3) {
            return [
                3 => 'Sub Admin',
            ];
        }

    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email','type'], 'required'],
            [['role_id','is_active', 'is_deleted'], 'integer'],
            [['type'], 'integer'],
            [['type'], 'in', 'range' => array_keys(self::getTypeOptions())],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['password_hash'], 'required', 'on' => 'create'],
            [['email', 'password', 'password_hash'], 'string', 'max' => 255],

            // Email validation rules for specific scenarios (create and update)
            ['email', 'email', 'message' => 'Please provide a valid email address'],
            ['email', 'validateEmail'],
            [['name', 'email', 'password'], 'string', 'max' => 150],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * Define scenarios for specific actions.
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        // Define create and update scenarios for email validation
        $scenarios['create'] = ['name', 'email', 'password', 'password_hash'];
        $scenarios['update'] = ['name', 'email', 'password'];

        return $scenarios;
    }

    public function validateEmail($attribute)
    {
        $modelEmail = Admins::find()
            ->where(['email' => $this->email, 'is_deleted' => 0]);
        if (!empty($this->id)) {
            $modelEmail->andWhere(['<>', 'id', $this->id]);
        }

        $modelEmail = $modelEmail->one();
        if (!empty($modelEmail)) {
            $this->addError($attribute, 'Admin with this email id already exist.');
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'parent_id' => 'Parent',
            'password' => 'Password',
            'password_hash' => 'Password',
            'access_token' => 'Access Token',
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Admins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildrens()
    {
        return $this->hasMany(Admins::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Admins::class, ['id' => 'parent_id']);
    }

    public function getRole()
    {
        return $this->hasOne(AuthRoles::class, ['id' => 'role_id']);
    }  

    public static function getRolesList()
    {

        $roles = AuthRoles::find()->where(['is_active' => 1, 'is_deleted' => 0])->all();
        $list = ArrayHelper::map($roles, 'id', 'name');
        return $list;

    }

    public static function getParentList()
    {
        // Get the currently logged-in user
        $user = Admins::findOne(Yii::$app->user->identity->id);
    
        if ($user->type == 3) {  // If the logged-in user is a subadmin
            // Return the subadmin itself as the parent for any new subadmin it creates
            $model = Admins::find()
                ->where(['id' => $user->id])  // Return only the logged-in subadmin
                ->orderBy(['name' => SORT_ASC])
                ->all();
        } else {  // If the user is a superadmin or admin
            // Fetch all the admins who have no parent (parent_id is null) (typically superadmin)
            $model = Admins::find()
                ->where(['is_active' => 1, 'is_deleted' => 0, 'parent_id' => null])
                ->orderBy(['name' => SORT_ASC])
                ->all();
        }
    
        // Map the result to return an associative array with 'id' as key and 'name' as value
        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
    
 

    public static function getAdminsList($type = null)
    {
        $user = Admins::findOne(Yii::$app->user->identity->id);

        // echo "<pre/>";print_r($user->descendants);exit;

        if ($type) {
            $model = Admins::find()
                ->where(['type' => $type, 'is_active' => 1, 'is_deleted' => 0])
                ->andWhere(['in', 'id', $user->descendants])
                ->orderBy(['name' => SORT_ASC])
                ->all();

        } else {

            $model = Admins::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->orderBy(['name' => SORT_ASC])
                ->all();
        }

        $list = ArrayHelper::map($model, 'id', 'name');
        return $list;
    }
    public function getIsParentAdmin()
    {
        return $this->parent_id === null; // If parent_id is null, this admin is a parent admin
    }

    public function getDescendants()
    {
        // Include the current admin's ID
        $descendants = [$this]; // Add the current admin object to the descendants array

        // Get all direct children
        $children = Admins::find()->where(['parent_id' => $this->id])->all();

        // Loop through each child
        foreach ($children as $child) {
            // Merge the child's descendants with the current list
            $descendants = array_merge($descendants, $child->getDescendants());
        }

        // Return the list including the current admin and all descendants
        return $descendants;
    }
}
