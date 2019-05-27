<?php declare(strict_types=1);

namespace Pulse\Components\Database;

use PDO;

class DatabaseSingleton extends ErrorHandlingDatabase
{
    private static $database;

    private function __construct()
    {
    }

    /**
     * @return PDO
     */
    public static function getDatabase(): PDO
    {
        if (self::$database == null) {
            // Establish connection
            // Define variables
            $hostName = 'localhost';
            $databaseName = 'pulse';
            $userName = 'pulse_root';
            $password = 'password';
            $charset = 'latin1';
            $dataSourceName = "mysql:host=$hostName;dbname=$databaseName;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            // Try to connect to database
            try {
                self::$database = new PDO($dataSourceName, $userName, $password, $options);
            } catch (\PDOException $e) {
                parent::handleErrors($e);
            }
        }
        return self::$database;
    }
}