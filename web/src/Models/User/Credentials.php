<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;
use Pulse\Exceptions;
use Pulse\Models\BaseModel;
use Pulse\Utils;

define('PEPPER', '14a5168782azxa5b4648de2chjufcb3afed6drt4');
define('CREDENTIALS_SALT_LENGTH', 40);

/**
 * Secured user who will implement 'user_credentials' table
 * This will make sure of
 * - hashing and storing user passwords
 * - authenticating a user
 * Class Credentials
 * @package Pulse\Models\BaseUser
 */
class Credentials implements BaseModel
{
    private $userId;
    private $password;
    private $salt;

    /**
     * Credentials constructor.
     * @param string $userId
     * @param string $password
     * @param string $salt
     */
    private function __construct($userId, $password, $salt)
    {
        $this->userId = $userId;
        $this->password = $password;
        $this->salt = $salt;
    }

    /**
     * Creates credentials
     * @param string $userId
     * @param string $password
     * @return Credentials|null
     * @throws Exceptions\UserNotExistException
     * @throws Exceptions\UserAlreadyExistsException
     */
    public static function fromNewCredentials(string $userId, string $password): Credentials
    {
        $query = DB::queryFirstRow("SELECT user_id from users WHERE user_id=%s", $userId);
        if ($query == null){
            // User not existing
            throw new Exceptions\UserNotExistException($userId);
        }

        $existingUser = DB::queryFirstRow("SELECT user_id from user_credentials WHERE user_id=%s", $userId);
        if ($existingUser != null) {
            // Credentials Existing
            throw new Exceptions\UserAlreadyExistsException($userId);
        }

        $salt = Utils::generateRandomString(CREDENTIALS_SALT_LENGTH);
        $credentials = new Credentials($userId, $password, $salt);
        $credentials->createCredentials();
        return $credentials;
    }

    /**
     * Reads credentials
     * @param string $userId
     * @param string $password
     * @return Credentials|null
     * @throws Exceptions\UserNotExistException
     */
    public static function fromExistingCredentials(string $userId, string $password): ?Credentials
    {
        $query = DB::queryFirstRow("SELECT salt from user_credentials WHERE user_id=%s", $userId);
        if ($query == null) {
            throw new Exceptions\UserNotExistException($userId);
        }
        $salt = $query['salt'];

        $credentials = new Credentials($userId, $password, $salt);
        return $credentials;
    }

    /**
     * Writes credential data to database
     */
    private function createCredentials()
    {
        DB::insert('user_credentials', array(
            'user_id' => $this->userId,
            'password' => $this->getHashedPassword(),
            'salt' => $this->salt
        ));
    }

    /**
     * Authenticate user by password
     * @return bool Whether user is authenticated
     */
    public function authenticate(): bool
    {
        $hashedPassword = $this->getHashedPassword();
        $userId = $this->userId;

        $query = DB::queryFirstRow("SELECT user_id from user_credentials WHERE user_id=%s AND password=%s", $userId, $hashedPassword);
        if ($query == null) {
            /// Unauthenticated
            return false;
        }
        return true;
    }

    /**
     * Hashes the password using salt and pepper
     * @return string Hashed password
     */
    private function getHashedPassword(): string
    {
        return hash('sha256', $this->userId . PEPPER . $this->password . $this->salt);
    }
}