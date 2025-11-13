<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\helpers;

use app\models\AuthRoles;
use app\models\Roles;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Description of PermissionHelper
 *
 * @author Akram Hossain
 */
class PermissionHelper
{

    static $host_menu = [
        '/dashboard',
        '/property-bookings',
        '/properties',
        '/property-details',
        '/payments',
    ];

    // static function checkUserHasRole($userId, $itemId, $type) {
    //     $model = \app\models\AuthAssignments::find()
    //             ->where(['auth_item_id' => $itemId, 'admin_id' => $userId])
    //             ->one();

    //     if (empty($model)) {
    //         return 0;
    //     } else {
    //         return 1;
    //     }
    // }

    // static function countUserModuleRole($userId, $moduleId, $type) {
    //     $model = \app\models\AuthAssignment::find()
    //             ->join('LEFT JOIN', 'auth_items', 'auth_items.id = auth_assignment.auth_item_id')
    //             ->where(['auth_module_id' => $moduleId, 'admin_id' => $userId])
    //             ->count();

    //     return $model;
    // }

    static function getUserPermission($role_id)
    {
        $roles = AuthRoles::findOne($role_id);
        if ($roles === null) {
            return []; // Return an empty array instead of calling getAuthPermissions() on null
        }
        $authAssignment = $roles->getAuthPermissions()->with(['authItems', 'authItems.authModules'])->asArray()->all();
        
        $result = [];
        foreach ($authAssignment as $row) {
            $roles = self::__formatAuthAssignment($row);
            array_push($result, $roles);
        }
        
        return $result;
    }
    
 


    static function checkUserHasAccessForMobile($controller_id, $method, $authAssignment)
    {
        $moduleArray = array_filter($authAssignment, function ($item) use ($controller_id, $method) {
            return $item['auth_module_id'] == $controller_id && $item['auth_item_name'] == $method;
        });

        if (count($moduleArray) > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function getUserModule($role_id)
    {
        $roles = AuthRoles::findOne($role_id);
        $authAssignment = $roles->getAuthPermissions()->with(['authItem', 'authItem.module'])->asArray()->all();
        $result = [];
        $ids = [];
        foreach ($authAssignment as $row) {
            //     $roles = self::__formatAuthAssignment($row);
            if (!in_array($row['authItem']['module']['id'], $ids)) {

                array_push($result, [
                    'id' => $row['authItem']['module']['id'],
                    'name' => $row['authItem']['module']['name'],
                    'image' => (string)\yii\helpers\BaseUrl::to(['/theme/img/menu/' . $row['authItem']['module']['icon']]),
                ]);

                array_push($ids, $row['authItem']['module']['id']);
            }
        }

        // debugPrint($result); exit;

        return $result;
    }

    static function __formatAuthAssignment($row)
    {
        return [
            'auth_item_id' => $row['auth_items_id'],
            'auth_item_name' => $row['authItems']['name'],
            'auth_item_url' => $row['authItems']['url'],
            'auth_module_id' => $row['authItems']['module_id'],
            'auth_module_url' => $row['authItems']['authModules']['url'],
            'full_url' => $row['authItems']['authModules']['url'] . $row['authItems']['url']
        ];
    }

    static function getPermissibleArrayOfUrl()
    {
        $json = \Yii::$app->session[Yii::$app->params['permissionItem']];
        $perm = json_decode($json, true);
        $result = [];
        if (!empty($perm)) {
            foreach ($perm as $row) {
                array_push($result, $row['full_url']);
            }
        }
        return $result;
    }


    static function getUserPermissibleAction($moduleId, $extra_action = [])
    {

        // if (\Yii::$app->session[Yii::$app->params['authName']] == 1) return array_merge(['index', 'view', 'create', 'update', 'delete', 'activate'], $extra_action);

        $json = \Yii::$app->session[Yii::$app->params['permissionItem']];
        $perm = json_decode($json, true);
        $actions = [];
        if (!empty($perm)) {
            foreach ($perm as $row) {
                if ($row['auth_module_id'] == $moduleId) {
                    $actions[] = str_replace(' ', '-', strtolower($row['auth_item_name'] == 'List' ? 'index' : $row['auth_item_name']));
                }
            }
        }
        // print_r($actions);exit;
        return array_merge($actions, $extra_action);
    }

    public static function getAllModuleList()
    {

        $model = \app\models\AuthModules::find()
            ->where(['is_deleted' => 0, 'is_active' => 1])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        $list = ArrayHelper::map($model, 'id', 'name');

        return $list;
    }

    static function checkUserHasAccess($controller, $method)
    {
        

        // if (\Yii::$app->session[Yii::$app->params['authName']] == 2) {
        $json = \Yii::$app->session[Yii::$app->params['permissionItem']];
        $perm = json_decode($json, true);
        if ($perm && !empty($perm)) {
            
            foreach ($perm as $item) {
                if ($item['full_url'] == '/' . $controller . '/' . $method) {
                    return true;
                }
            }
        }
        return false;
        // } else {
        //     return true;
        // }
    }

    public static function getUserPermissionsString($controllerId, $userId = null)
    {
        if ($userId === null) {
            $userId = Yii::$app->user->identity->id;
        }

        $permissions = [
            'create' => self::checkUserHasAccess($controllerId, 'create'),
            'update' => self::checkUserHasAccess($controllerId, 'update'),
            'delete' => self::checkUserHasAccess($controllerId, 'delete'),
            'view' => self::checkUserHasAccess($controllerId, 'view', $userId),
            'duplicate' => self::checkUserHasAccess($controllerId, 'duplicate', $userId),
        ];

        $permissionStr = '';
        if ($permissions['view']) {
            $permissionStr .= '{view} ';
        }
        if ($permissions['update']) {
            $permissionStr .= '{update} ';
        }
        if ($permissions['duplicate']) {
            $permissionStr .= '{duplicate} ';
        }
        if ($permissions['delete']) {
            $permissionStr .= '{delete} ';
        }

        return [
            'permissionStr' => $permissionStr,
            'permissions' => $permissions
        ];
    }
}
