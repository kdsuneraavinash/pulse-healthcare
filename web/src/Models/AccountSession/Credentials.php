<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Exceptions;
use Pulse\Models\BaseModel;
use Pulse\Utils;

define('PEPPER', '14a5168782azxa5b4648de2chjufcb3afed6drt4');
define('CREDENTIALS_SALT_LENGTH', 40);

/**
 * Secured account who will implement 'account_credentials' table
 * This will make sure of
 * - hashing and storing account passwords
 * - authenticating a account
 * Class Credentials
 */
class Credentials implements BaseModel
{
    private $accountId;
    private $password;
    private $salt;

    /**
     * Credentials constructor.
     * @param string $accountId
     * @param string $password
     * @param string $salt
     */
    private function __construct($accountId, $password, $salt)
    {
        $this->accountId = $accountId;
        $this->password = $password;
        $this->salt = $salt;
    }

    /**
     * Creates credentials
     * @param string $accountId
     * @param string $password
     * @return Credentials|null
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountAlreadyExistsException
     */
    public static function fromNewCredentials(string $accountId, string $password): Credentials
    {
        $query = DB::queryFirstRow("SELECT account_id from accounts WHERE account_id=%s", $accountId);
        if ($query == null) {
            // Account not existing
            throw new Exceptions\AccountNotExistException($accountId);
        }

        $existingAccount = DB::queryFirstRow("SELECT account_id from account_credentials WHERE account_id=%s", $accountId);
        if ($existingAccount != null) {
            // Credentials Existing
            throw new Exceptions\AccountAlreadyExistsException($accountId);
        }

        $salt = Utils::generateRandomString(CREDENTIALS_SALT_LENGTH);
        $credentials = new Credentials($accountId, $password, $salt);
        $credentials->createCredentials();

        return $credentials;
    }

    /**
     * Reads credentials
     * @param string $accountId
     * @param string $password
     * @return Credentials
     * @throws Exceptions\AccountNotExistException
     */
    public static function fromExistingCredentials(string $accountId, string $password): Credentials
    {
        $query = DB::queryFirstRow("SELECT salt from account_credentials WHERE account_id=%s", $accountId);
        if ($query == null) {
            throw new Exceptions\AccountNotExistException($accountId);
        }
        $salt = $query['salt'];

        $credentials = new Credentials($accountId, $password, $salt);
        return $credentials;
    }

    /**
     * Writes credential data to database
     */
    private function createCredentials()
    {
        DB::insert('account_credentials', array(
            'account_id' => $this->accountId,
            'password' => $this->getHashedPassword(),
            'salt' => $this->salt
        ));
        Utils::getLogger()->info("Credentials created for user $this->accountId");
    }

    /**
     * Authenticate account by password
     * @return bool Whether account is authenticated
     */
    public function authenticate(): bool
    {
        $hashedPassword = $this->getHashedPassword();
        $accountId = $this->accountId;

        $query = DB::queryFirstRow("SELECT account_id from account_credentials WHERE account_id=%s AND password=%s", $accountId, $hashedPassword);

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
        return hash('sha256', $this->accountId . PEPPER . $this->password . $this->salt);
    }
}