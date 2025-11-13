<?php

namespace app\modules\api\helpers;

class ApplicationHelper
{
    public static function _formatUser($user){
        return [
            "id" => $user['id'],
            "name" => (String)$user->name,
            "phone_no" => (String)$user->phone_no,
            "last_name" => (String)$user->last_name,
            "employee_id" => (String)$user->employee_id,
            "email" => (String)$user->email,
            "eligible_km" => (String)$user->eligible_km,
            'user_type' => self::convertUserTypeToNumber($user->user_type),
            "device_id" => (String)$user->device_id,
            "device_type" => (String)$user->device_type,
            "device_model" => (String)$user->device_model,
            "app_version" => (String)$user->app_version,
            "os_version" => (String)$user->os_version,
            "access_token" => (String)$user->access_token,
            "created_at" => (String)$user->created_at,
            "updated_at" => (String)$user->updated_at,
            "profile" => (String)$user->profile,
        ];
    }

    /**
     * Convert user type string to numeric value
     * @param string $userType
     * @return int
     */
    public static function convertUserTypeToNumber($userType) {
        $userTypeMap = [
            'sales' => 1,
            'office' => 2,
            'sales_and_office' => 3
        ];
        
        return isset($userTypeMap[$userType]) ? $userTypeMap[$userType] : 0;
    }

    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Earth's radius in kilometers
    
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
    
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        return $earthRadius * $c; // Returns distance in kilometers
    }
}