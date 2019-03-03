<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use Pulse\Components\Database;
use Pulse\Components\Utils;
use Pulse\Definitions;
use Pulse\Models\BaseModel;
use Pulse\Models\Exceptions;


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
        $query = Database::queryFirstRow("SELECT account_id from accounts WHERE account_id=:account_id",
            array('account_id' => $accountId));

        if ($query == null) {
            // Account not existing
            throw new Exceptions\AccountNotExistException($accountId);
        }

        $existingAccount = Database::queryFirstRow("SELECT account_id from account_credentials WHERE account_id=:account_id",
            array('account_id' => $accountId));

        if ($existingAccount != null) {
            // Credentials Existing
            throw new Exceptions\AccountAlreadyExistsException($accountId);
        }

        $salt = Utils::generateRandomSaltyString(Definitions::CREDENTIALS_SALT_LENGTH);
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
        $query = Database::queryFirstRow("SELECT salt from account_credentials WHERE account_id=:account_id",
            array('account_id' => $accountId));

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
        Database::insert('account_credentials', array(
            'account_id' => $this->accountId,
            'password' => $this->getHashedPassword(),
            'salt' => $this->salt
        ));
    }

    /**
     * Authenticate account by password
     * @return bool Whether account is authenticated
     */
    public function authenticate(): bool
    {
        $hashedPassword = $this->getHashedPassword();
        $accountId = $this->accountId;

        $query = Database::queryFirstRow("SELECT account_id from account_credentials " .
            "WHERE account_id=:account_id AND password=:password",
            array('account_id' => $accountId, 'password' => $hashedPassword));

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
        return hash('sha256', $this->accountId . Definitions::PEPPER . $this->password . $this->salt);
    }
}