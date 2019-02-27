<?php declare(strict_types=1);

namespace Pulse\Components;

use DB;
use PDO;

class Database
{
    private static $connection;

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

//        $host = 'localhost';
//        $db = 'pulse';
//        $user = 'pulse_root';
//        $password = 'password';
//        $charset = 'latin1';
//
//        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//        $options = [
//            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//            PDO::ATTR_EMULATE_PREPARES => false,
//        ];
//
//        try {
//            self::$connection = new PDO($dsn, $user, $password, $options);
//
//            $statement = self::$connection->query('SELECT account_id FROM accounts');
//            $string = "";
//            while ($row = $statement->fetch())
//            {
//                $string .= $row['account_id'] . ". ";
//            }
//            Logger::log($string);
//        } catch (\PDOException $e) {
//            self::handleErrors($e);
//        }
    }

    private static function handleErrors(\Exception $e)
    {
        echo str_replace('[DATABASE_ERROR]', $e->getMessage(), file_get_contents("database_error.html"));
        Logger::log("Error[{$e->getCode()}] {$e->getMessage()}", Logger::ERROR, 'Database');
        die;
        //        throw $e;
    }

    public static function dispose()
    {
        self::$connection = null;
    }

    public static function query(string $statement, array $params){
        $statement = self::getDatabase()->prepare($statement);

        foreach ($params as $key=>$value){
            if (is_int($value)){
                $datatype = PDO::PARAM_INT;
            }else{
                $datatype = PDO::PARAM_STR;
            }
            $statement->bindParam(":$key", $value, $datatype);
        }
    }

    private static function getDatabase():PDO
    {
        return self::$connection;
    }
}