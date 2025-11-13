<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $employee_id
 * @property string $phone_no
 * @property string $user_type
 * @property string $profile
 * @property string $address
 * @property string $vehicle_type
 * @property string $da_amount
 * @property string $da_with_night_stay_amount
 * @property string $eligible_km
 * @property int|null $parent_id
 * @property string|null $device_id
 * @property string|null $device_type
 * @property string|null $device_model
 * @property string|null $app_version
 * @property string|null $os_version
 * @property string|null $device_token
 * @property string|null $access_token
 * @property int $is_active
 * @property int $is_deleted
 * @property string|null $created_at
 * @property string|null $punch_in_date
 * @property string|null $updated_at
 * @property int $employee_grade_id
 * @property int $office_punchin_enabled
 *
 * @property Admins $parent
 * @property EmployeeGrades $employeeGrade
 */
class Users extends \yii\db\ActiveRecord
{
    public $image; // Temporary attribute for file upload

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'parent_id', 'last_name', 'employee_id', 'phone_no', 'user_type', 'da_amount', 'da_with_night_stay_amount', 'eligible_km', 'employee_grade_id'], 'required'],
            [['parent_id', 'is_active', 'is_deleted', 'employee_grade_id', 'office_punchin_enabled'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'device_model'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['device_id', 'device_token', 'profile', 'address', 'access_token'], 'string', 'max' => 255],
            [['device_type', 'app_version', 'os_version'], 'string', 'max' => 50],
            [['user_type'], 'in', 'range' => ['sales', 'office', 'sales_and_office']],
            ['email', 'email', 'message' => 'Please provide a valid email address'],
            [['da_amount', 'da_with_night_stay_amount', 'eligible_km'], 'number'],
            ['email', 'validateEmail'],
            ['phone_no', 'validatePhone'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Admins::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024], // Max 2MB

        ];
    }

    public function uploadImage()
    {
        $imageFile = UploadedFile::getInstance($this, 'image');

        if ($imageFile) {
            Yii::debug("Uploading image: " . $imageFile->name); // Debugging line

            $fileName = 'uploads/profile_' . time() . '.' . $imageFile->extension;

            if ($imageFile->saveAs($fileName)) {
                Yii::debug("Image saved at: " . $fileName); // Debugging line
                return $fileName;
            } else {
                Yii::debug("Failed to save image."); // Debugging line
            }
        }

        return false;
    }


    public function validateEmail($attribute)
    {
        $modelEmail = Users::find()
            ->where(['email' => $this->email, 'is_deleted' => 0]);
        if (!empty($this->id)) {
            $modelEmail->andWhere(['<>', 'id', $this->id]);
        }

        $modelEmail = $modelEmail->one();
        if (!empty($modelEmail)) {
            $this->addError($attribute, 'User with this email id already exist.');
        }
    }
    
    public function validatePhone($attribute)
    {
        $modelEmail = Users::find()
            ->where(['phone_no' => $this->phone_no, 'is_deleted' => 0]);
        if (!empty($this->id)) {
            $modelEmail->andWhere(['<>', 'id', $this->id]);
        }

        $modelEmail = $modelEmail->one();
        if (!empty($modelEmail)) {
            $this->addError($attribute, 'User with this phone no already exist.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'First Name',
            'last_name' => 'Last Name',
            'employee_id' => 'Employee Id',
            'employee_grade_id' => 'Employee Grade',
            'email' => 'Email',
            'phone_no' => 'Phone No',
            'user_type' => 'User Type',
            'profile' => 'Profile',
            'address' => 'Address',
            'parent_id' => 'Parent',
            'eligible_km' => 'Eligible Km',
            'da_amount' => 'Da Amount',
            'da_with_night_stay_amount' => 'Da With Night Stay Amount',
            'device_id' => 'Device ID',
            'device_type' => 'Device Type',
            'device_model' => 'Device Model',
            'app_version' => 'App Version',
            'os_version' => 'Os Version',
            'access_token' => 'Access Token',
            'device_token' => 'Device Token',
            'is_active' => 'Status',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Date',
            'updated_at' => 'Updated At',
            'office_punchin_enabled' => 'Office Punch-in Enabled',
        ];
    }

    /**  
     * Gets query for [[EmployeeGrade]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeGrade()
    {
        return $this->hasOne(EmployeeGrades::class, ['id' => 'employee_grade_id']);
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

        public static function getUserList()
        {
            $model = Users::find()
                ->where(['is_active' => 1, 'is_deleted' => 0])
                ->orderBy(['name' => SORT_ASC])
                ->all();

            $list = ArrayHelper::map($model, 'id', 'name');
            return $list;
        }

    public function getEmployeePunchDetails()
    {
        return $this->hasOne(EmployeePunchDetails::class, ['user_id' => 'id']);
    }

    public function getGrade()
    {
        // return Grades::find()->where(['id' => $this->employee_grade_id])->one();
        return Grades::findOne($this->employee_grade_id);
    }

    public function getParents()
    {

        $parents = [];
        $parent = Admins::findOne(['id' => $this->parent_id]);

        while ($parent) {
            $parents[] = $parent;

            $parent = Admins::findOne(['id' => $parent->parent_id]);
        }

        return $parents;
    }

    /**
     * Get user type options
     * @return array
     */
    public static function getUserTypeOptions()
    {
        return [
            'sales' => 'Sales',
            'office' => 'Office',
            'sales_and_office' => 'Sales & Office',
        ];
    }

    /**
     * Get user type label
     * @return string
     */
    public function getUserTypeLabel()
    {
        $options = self::getUserTypeOptions();
        return isset($options[$this->user_type]) ? $options[$this->user_type] : $this->user_type;
    }
}
