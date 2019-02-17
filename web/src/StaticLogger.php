<?php

namespace Pulse;

use Monolog\Logger;

class StaticLogger{

    private static $logger;

    /**
     * @return mixed
     */
    private static function getLogger(): Logger
    {
        return self::$logger;
    }

    public static function loggerWarn(string $message)
    {
        if (self::$logger == null) return;
        self::getLogger()->warn($message);
    }

    public static function loggerInfo(string $message)
    {
        if (self::$logger == null) return;
        self::getLogger()->info($message);
    }

    public static function loggerError(string $message)
    {
        if (self::$logger == null) return;
        self::getLogger()->error($message);
    }

    public static function loggerDebug(string $message)
    {
        if (self::$logger == null) return;
        self::getLogger()->debug($message);
    }

    /**
     * @return mixed
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }
}