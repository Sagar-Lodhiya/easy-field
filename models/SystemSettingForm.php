<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class SystemSettingForm extends Model
{
    public $employee_add_limit;
    public $sub_admin_add_limit;
    public $traveling_rate_car;
    public $is_force_update;

    public $traveling_rate_bike;
    public $ping_interval;
    public $video_tutorial;
    public $app_version;
    public $under_maintenance;
    public $location;
    public $distance;




    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['employee_add_limit', 'sub_admin_add_limit', 'traveling_rate_car', 'traveling_rate_bike', 'ping_interval', 'app_version', 'video_tutorial', 'location','distance', 'under_maintenance'], 'required'],
            [['is_force_update', 'under_maintenance'], 'integer', 'min' => 0, 'max' => 1],




        ];
    }

    public function loadSetting()
    {
        $model = Settings::find()->where(['id' => ['1', '2', '3', '4', '5', '6', '7','8','9', '8', '9']])->all();

        $this->employee_add_limit = $model[0]->value;

        $this->sub_admin_add_limit = $model[1]->value;

        $this->traveling_rate_car = $model[2]->value;

        $this->traveling_rate_bike = $model[3]->value;

        $this->ping_interval = $model[4]->value;

        $this->app_version = $model[5]->value;

        $this->video_tutorial = $model[6]->value;

        $this->is_force_update = $model[7]->value;
       
        $this->under_maintenance = $model[8]->value;

        $this->location=$model[7]->value;

        $this->distance=$model[8]->value;


    }


    public function saveData()
    {
        $employeeAddLimitModel = Settings::findOne(1);
        $employeeAddLimitModel->value = $this->employee_add_limit;
        $employeeAddLimitModel->save();

        $subAdminAddLimitModel = Settings::findOne(2);
        $subAdminAddLimitModel->value = $this->sub_admin_add_limit;
        $subAdminAddLimitModel->save();

        $traveling_rate_car = Settings::findOne(3);
        $traveling_rate_car->value = $this->traveling_rate_car;
        $traveling_rate_car->save();

        $traveling_rate_bike = Settings::findOne(4);
        $traveling_rate_bike->value = $this->traveling_rate_bike;
        $traveling_rate_bike->save();

        $ping_interval = Settings::findOne(5);
        $ping_interval->value = $this->ping_interval;
        $ping_interval->save();


        $app_version = Settings::findOne(6);
        $app_version->value = $this->app_version;
        $app_version->save();

        $video_tutorial = Settings::findOne(7);
        $video_tutorial->value = $this->video_tutorial;
        $video_tutorial->save();

        $location = Settings::findOne(8);
        $location->value = $this->location;
        $location->save();

        $distance = Settings::findOne(9);
        $distance->value = $this->distance;
        $distance->save();

        $app_version = Settings::findOne(8);
        $app_version->value = $this->is_force_update;
        $app_version->save();

        $app_version = Settings::findOne(9);
        $app_version->value = $this->under_maintenance;
        $app_version->save();



        return true;

    }

    public function store()
    {
        if ($this->validate()) {
            return $this->saveData();


        } else {
            return false;
        }
    }
}
