<?php declare(strict_types=1);

namespace Pulse;

use Pulse;

define('MIN_CHR_ASCII', 33);
define('MAX_CHR_ASCII', 126);

class Utils
{
    public static function generateRandomString($length)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            try {
                $random .= chr(random_int(MIN_CHR_ASCII, MAX_CHR_ASCII));
            } catch (\Exception $e) {
                $random = chr(mt_rand(MIN_CHR_ASCII, MAX_CHR_ASCII));
            }
        }
        return $random;
    }

    public static function getClientIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            return $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            return $_SERVER['REMOTE_ADDR'];
        else
            return 'UNKNOWN';
    }

    public static function getUserAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT']))
            return $_SERVER['HTTP_USER_AGENT'];
        else
            return 'UNKNOWN';
    }
}