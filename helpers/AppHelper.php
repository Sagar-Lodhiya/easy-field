<?php

namespace app\helpers;

class AppHelper
{
    public static $statusOptions = [1 => 'Active', 0 => 'Inactive'];
    public static $typeOptions = ['NEFT' => 'NEFT', "RTGS" => 'RTGS', 'CHECK' => 'CHECK'];
    public static $yesNoOptions = [1 => 'Yes', 0 => 'No'];
    public static $genderOptions = ['M' => 'Male', 'F' => 'Female'];
    public static $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    public static function generateBookingID($prefix = 'OSG')
    {
        // Generate a timestamp-based part
        $timestamp = time();

        // Generate a random string part
        $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

        // Combine the parts with the prefix to form the booking ID
        $bookingID = $prefix . $timestamp . $randomString;

        return $bookingID;
    }
    public static function getCompanyId($company)
    {
        switch ($company) {
            case 'all':
                return null;
                break;

            case 'anbr1':
                return 10;
                break;

            case 'anbr2':
                return 7;
                break;

            case 'aaspl':
                return 4;
                break;

            case 'absipl':
                return 12;
                break;

            case 'abfpl':
                return 12;
                break;

            default:
                return null;
                break;
        }
    }

    public static function getStatusLabel($status_id)
    {
        switch ($status_id) {
            case '1':
                return '<span class="label label-warning">Pending</span>';
                break;

            case '2':
                return '<span class="label label-primary">Process</span>';
                break;

            case '3':
                return '<span class="label label-success">Ready</span>';
                break;

            case '4':
                return '<span class="label label-secondary">Delivered</span>';
                break;

            default:
                return '<span class="label label-warning">Pending</span>';
                break;
        }
    }
}
