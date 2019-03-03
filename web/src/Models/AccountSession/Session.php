<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use Pulse\Components\Database;
use Pulse\Components\Utils;
use Pulse\Definitions;
use Pulse\Models\BaseModel;
use Pulse\Models\Exceptions;

class Session implements BaseModel
{
    private $account;
    private $sessionKey;

    /**
     * Session constructor.
     * @param string $accountId id of the account
     * @param string $sessionKey Session key
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    private function __construct(string $accountId, string $sessionKey)
    {
        // Get account accordingly
        $this->account = Account::retrieveAccount($accountId);
        if (!$this->account->exists()) {
            // If the account does not exist
            throw new Exceptions\AccountNotExistException($accountId);
        }
        $this->sessionKey = $sessionKey;
    }

    /**
     * Create a new account session
     * @param string $accountId ID of the account to create session
     * @return Session Created Session Object
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public static function createSession(string $accountId): Session
    {
        $ipAddress = Utils::getClientIP();
        $sessionKey = Session::getEncryptedSessionKey($accountId, $ipAddress);

        // Get the row with corresponding session key (if exists)
        $query = Database::queryFirstRow('SELECT session_key FROM sessions ' .
            'WHERE account_id = :account_id AND ip_address= :ip_address',
            array('account_id' => $accountId, 'ip_address' => $ipAddress)
        );

        if ($query != null) {
            // Exists: Get existing data - Must Not Update since old session keys will become invalid
            $sessionKey = $query['session_key'];
        } else {
            // Does Not Exist: Insert session
            Database::insert(
                'sessions',
                array(
                    'account_id' => $accountId,
                    'ip_address' => $ipAddress,
                    'created' => Database::sqleval("NOW()"),
                    'expires' => Database::sqleval("ADDDATE(NOW(), " . Definitions::USER_EXPIRATION_DAYS . ")"),
                    'session_key' => $sessionKey),
                false);
        }

        return new Session($accountId, $sessionKey);
    }

    /**
     * Resumes an account session
     * @param string $accountId BaseAccount Id to resume session
     * @param string $sessionKey Session Key of the session to resume
     * @return Session|null Created session(null if session key is invalid)
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public static function resumeSession(string $accountId, string $sessionKey): ?Session
    {
        $ipAddress = Utils::getClientIP();

        // Get session details for DB(check for expiration)
        $query = Database::queryFirstRow('SELECT session_key FROM sessions ' .
            'WHERE account_id = :account_id AND ip_address = :ip_address AND ' .
            'session_key = :session_key AND expires > NOW() ',
            array(
                'account_id' => $accountId,
                'ip_address' => $ipAddress,
                'session_key' => $sessionKey
            )
        );

        // Return null if session didn't exist
        if ($query == null) {
            return null;
        }

        return new Session($accountId, $sessionKey);
    }

    /**
     * Close a account session
     */
    public function closeSession()
    {
        Session::closeSessionOfContext($this->getSessionAccount()->getAccountId(), $this->getSessionKey());
    }

    /**
     * Close a account session
     * @param string $id
     * @param string $sessionKey
     */
    public static function closeSessionOfContext(string $id, string $sessionKey)
    {
        Database::delete('sessions',
            "account_id = :account_id AND session_key = :session_key",
            array('account_id' => $id, 'session_key' => $sessionKey));
    }

    /**
     * Generate a session key using a account-defined salt
     * @param string $accountId BaseAccount id of the account to generate session key
     * @param string $ip IP of the session
     * @return string Generated session key
     */
    private static function getEncryptedSessionKey(string $accountId, string $ip): string
    {
        $salt = Utils::generateRandomSaltyString(Definitions::SESSION_SALT_LENGTH);
        return sha1($salt . time() . $accountId . $ip);
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * @return Account
     */
    public function getSessionAccount(): Account
    {
        return $this->account;
    }
}