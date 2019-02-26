<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Definitions;
use Pulse\Models\Exceptions;
use Pulse\Models\BaseModel;
use Pulse\Utils;

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
        $this->account = Account::retrieveAccount($accountId);
        if (!$this->account->exists()) {
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

        $primaryKey = array(
            'account_id' => $accountId,
            'ip_address' => $ipAddress);
        $record = array(
            'created' => DB::sqleval("NOW()"),
            'expires' => DB::sqleval("ADDDATE(NOW(), " . Definitions::USER_EXPIRATION_DAYS . ")"),
            'session_key' => $sessionKey
        );

        $query = DB::queryFirstRow('SELECT session_key FROM sessions WHERE account_id=%s AND ip_address=%s',
            $accountId, $ipAddress);
        if ($query != null) {
            // Exists: Get existing data - Must Not Update since old session keys will become invalid
            $sessionKey = $query['session_key'];
        } else {
            // Does Not Exist: Insert session
            DB::insert('sessions', array_merge($primaryKey, $record));
        }

        return new Session($accountId, $sessionKey);
    }

    /**
     * Resumes a account session
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

        $query = DB::queryFirstRow('SELECT session_key FROM sessions ' .
            'WHERE account_id = %s AND ip_address = %s AND ' .
            'session_key = %s AND expires > NOW() ',
            $accountId, $ipAddress, $sessionKey);

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
        DB::delete('sessions', "account_id = %s AND session_key = %s", $id, $sessionKey);
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