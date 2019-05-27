<?php declare(strict_types=1);

namespace Pulse\Components\Database;

use Pulse\Components\Logger;

abstract class ErrorHandlingDatabase
{
    /**
     * Private database error handler
     * @param \Exception $e
     */
    protected static function handleErrors(\Exception $e)
    {
        Logger::log("Error[{$e->getCode()}] {$e->getMessage()}", Logger::ERROR, 'Database');
        echo str_replace('[DATABASE_ERROR]', $e->getMessage(), file_get_contents("database_error.html"));
        die;
    }
}