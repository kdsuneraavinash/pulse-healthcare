<?php declare(strict_types=1);

namespace Pulse;

use DB;

class Database
{
    public static function init()
    {
        DB::$error_handler = 'databaseErrorHandler';
        DB::$nonsql_error_handler = 'databaseErrorHandler';
        DB::$user = 'pulse_root';
        DB::$password = 'password';
        DB::$dbName = 'pulse';
        DB::$host = 'localhost';
        DB::$port = '3306';
        DB::$encoding = 'latin1';
    }
}