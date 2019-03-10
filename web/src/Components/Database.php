<?php declare(strict_types=1);

namespace Pulse\Components;

use PDO;

class Database
{
    private static $database;

    public static function init()
    {
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
//        try {
//            self::$database = new PDO($dataSourceName, $userName, $password, $options);
//        } catch (\PDOException $e) {
//            self::handleErrors($e);
//        }
        self::$database = new PDO($dataSourceName, $userName, $password, $options);
    }

    /**
     * Private database error handler
     * @param \Exception $e
     */
    private static function handleErrors(\Exception $e)
    {
        echo str_replace('[DATABASE_ERROR]', $e->getMessage(), file_get_contents("database_error.html"));
        Logger::log("Error[{$e->getCode()}] {$e->getMessage()}", Logger::ERROR, 'Database');
        die;
    }

    /**
     * Serves to escape mysql statements
     * @param string $statement
     * @return PureSqlStatement
     */
    public static function sqleval(string $statement)
    {
        return new PureSqlStatement($statement);
    }

    /**
     * Include pure sql statements (security risk - DO NOT pass user variables inside)
     * @param string $query
     * @param array $params
     * @return string
     * @throws \Exception
     */
    private static function includePureSqlStatements(string $query, array $params): string
    {
        foreach ($params as $key => $value) {
            if ($value instanceof PureSqlStatement) {
                $replace_counter = substr_count($query, ":$key");
                if ($replace_counter > 1){
                    throw new \Exception("Pure SQL statement error. Replaces more than one instances.");
                }
                $count = 1;
                $query = str_replace(":$key", $value->getStatement(), $query, $count);
            }
        }
        return $query;
    }

    private static function bindToStatement(\PDOStatement $statement, array $params)
    {
        foreach ($params as $key => $value) {
            if ($value instanceof PureSqlStatement) {
                continue;
            }
            $statement->bindValue(":$key", $value);
        }
    }

    /**
     * Returns all rows corresponding to query
     * Database::query('SELECT * FROM users WHERE id = :id', array('id' => $id))
     * @param string $query
     * @param array $params
     * @return array
     */
    public static function query(string $query, array $params)
    {
        try {
            $statement = self::getDatabase()->prepare($query);
            self::bindToStatement($statement, $params);
            $statement->execute();
            // Get all rows
            return $statement->fetchAll();
        } catch (\Exception $e) {
            self::handleErrors($e);
            exit;
        }
    }

    /**
     * Returns first row corresponding to query
     * Database::queryFirstRow('SELECT * FROM users WHERE id = :id', array('id' => $id))
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function queryFirstRow(string $query, array $params)
    {
        try {
            $statement = self::getDatabase()->prepare($query);
            self::bindToStatement($statement, $params);
            $statement->execute();
            // Get only first row
            $result = $statement->fetch();
            if ($result == false) {
                $result = null;
            }
            return $result;
        } catch (\Exception $e) {
            self::handleErrors($e);
            exit;
        }
    }

    /**
     * Database::insert('users', array('id' => $id, 'name' => $name))
     * Set $ignorePureSqlStatements to false to include sqleval() statements inside.
     * @param string $table
     * @param array $params
     * @param bool $ignorePureSqlStatements
     */
    public static function insert(string $table, array $params, bool $ignorePureSqlStatements = true)
    {
        try {
            /// Syntax = INSERT INTO users (id, name) VALUES (:id, :name)

            // Start of the statement
            $query = "INSERT INTO " . $table . " ";

            // Field names
            $arrParams = array();
            foreach ($params as $key => $value) {
                array_push($arrParams, $key);
            }
            $query .= "(" . join(", ", $arrParams) . ") VALUES ";

            // Field values
            $arrParams = array();
            foreach ($params as $key => $value) {
                array_push($arrParams, ":$key");
            }
            $query .= "(" . join(", ", $arrParams) . ")";

            // Pass Pure Sql Statements if requested
            if (!$ignorePureSqlStatements) {
                $query = self::includePureSqlStatements($query, $params);
            }

            // Execute
            $statement = self::getDatabase()->prepare($query);
            self::bindToStatement($statement, $params);
            $statement->execute();
        } catch (\Exception $e) {
            self::handleErrors($e);
            exit;
        }
    }

    /**
     * Database::update('users', 'name=:name', 'id=:id', array('id' => $id, 'name' => $name))
     * @param string $table
     * @param string $set
     * @param string $where
     * @param array $params
     */
    public static function update(string $table, string $set, string $where, array $params)
    {
        try {
            /// Syntax = UPDATE users SET name=:name WHERE id=:id
            $query = "UPDATE " . $table . " SET $set WHERE $where";
            $statement = self::getDatabase()->prepare($query);
            self::bindToStatement($statement, $params);
            $statement->execute();
        } catch (\Exception $e) {
            self::handleErrors($e);
            exit;
        }
    }

    /**
     * Database::delete('users', 'id = :id AND name = :name', array('id' => $id, 'name' => $name))
     * @param string $table
     * @param string $where
     * @param array $params
     */
    public static function delete(string $table, string $where, array $params)
    {
        try {
            $query = "DELETE FROM $table WHERE $where";
            $statement = self::getDatabase()->prepare($query);
            self::bindToStatement($statement, $params);
            $statement->execute();
        } catch (\Exception $e) {
            self::handleErrors($e);
            exit;
        }
    }

    /**
     * @return PDO
     */
    private static function getDatabase(): PDO
    {
        return self::$database;
    }
}

class PureSqlStatement
{
    private $statement;

    public function __construct(string $statement)
    {
        $this->statement = $statement;
    }

    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->statement;
    }
}