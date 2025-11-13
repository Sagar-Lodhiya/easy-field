<?php

namespace app\helpers;

class LocationHelper
{
    public static function encodePolyline($points) {
        $encoded = '';
        $prevLat = 0;
        $prevLng = 0;
    
        foreach ($points as $point) {
            $lat = round($point['latitude'] * 1e5);
            $lng = round($point['longitude'] * 1e5);
    
            $dLat = $lat - $prevLat;
            $dLng = $lng - $prevLng;
    
            $encoded .= self::encodeSignedNumber($dLat);
            $encoded .= self::encodeSignedNumber($dLng);
    
            $prevLat = $lat;
            $prevLng = $lng;
        }
    
        return $encoded;
    }
    
    public static function encodeSignedNumber($num) {
        $num = $num << 1;
        if ($num < 0) {
            $num = ~$num;
        }
        return self::encodeNumber($num);
    }
    
    public static function encodeNumber($num) {
        $encoded = '';
        while ($num >= 0x20) {
            $encoded .= chr((0x20 | ($num & 0x1f)) + 63);
            $num >>= 5;
        }
        $encoded .= chr($num + 63);
        return $encoded;
    }
}