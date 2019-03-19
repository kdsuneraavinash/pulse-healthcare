<?php declare(strict_types=1);

namespace Pulse\Components;

define('MIN_CHR_ASCII', 33);
define('MAX_CHR_ASCII', 126);
define('MIN_LETTER_ASCII', 97);
define('MAX_LETTER_ASCII', 122);

class Utils
{
    /**
     * @param int $length
     * @param int $lowerLimit
     * @param int $upperLimit
     * @return string
     */
    private static function generateRandomString(int $length, int $lowerLimit, int $upperLimit)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            try {
                $random .= chr(random_int($lowerLimit, $upperLimit));
            } catch (\Exception $e) {
                $random = chr(mt_rand($lowerLimit, $upperLimit));
            }
        }
        return $random;
    }

    public static function generateRandomSaltyString($length)
    {
        return Utils::generateRandomString($length, MIN_CHR_ASCII, MAX_CHR_ASCII);
    }

    public static function generateRandomReadableString($length)
    {
        return Utils::generateRandomString($length, MIN_LETTER_ASCII, MAX_LETTER_ASCII);
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

    public static function array2string($data)
    {
        $log_a = "";
        foreach ($data as $key => $value) {
            if (is_array($value)) $log_a .= "[" . $key . "] => (" . self::array2string($value) . ") \n";
            else                    $log_a .= "[" . $key . "] => " . $value . "\n";
        }
        return $log_a;
    }
}