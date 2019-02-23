<?php declare(strict_types=1);

namespace Pulse\Models\AccountSession;

use DB;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Models\BaseModel;
use Pulse\Utils;

define('USER_EXPIRATION_DAYS', 1);
define('SESSION_SALT_LENGTH', 40);


class Session implements BaseModel
{
    private $account;
    private $sessionKey;

    /**
     * Session constructor.
     * @param string $accountId id of the account
     * @param string $sessionKey Session key
     * @throws AccountNotExistException if account does not exist
     */
    private function __construct(string $accountId, string $sessionKey)
    {
        $this->account = Account::retrieveAccount($accountId);
        if (! $this->account->exists()) {
            throw new AccountNotExistException($accountId);
        }
        $this->sessionKey = $sessionKey;
    }

    /**
     * Create a new account session
     * @param string $accountId ID of the account to create session
     * @return Session Created Session Object
     * @throws AccountNotExistException
     */
    public static function createSession(string $accountId): Session
    {
        $ipAddress = Utils::getClientIP();
        $browserAgent = BrowserAgent::fromCurrentBrowserAgent();
        $sessionKey = Session::getEncryptedSessionKey($accountId, $browserAgent, $ipAddress);

        $primaryKey = array(
            'account_id' => $accountId,
            'ip_address' => $ipAddress,
            'browser_agent' => $browserAgent->getId());
        $record = array(
            'created' => DB::sqleval("NOW()"),
            'expires' => DB::sqleval("ADDDATE(NOW(), " . USER_EXPIRATION_DAYS . ")"),
            'session_key' => $sessionKey
        );

        $query = DB::queryFirstRow('SELECT session_key FROM sessions WHERE account_id=%s AND ip_address=%s AND browser_agent=%i',
            $accountId, $ipAddress, $browserAgent->getId());
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
     * @throws AccountNotExistException
     */
    public static function resumeSession(string $accountId, string $sessionKey): ?Session
    {
        $ipAddress = Utils::getClientIP();
        $browserAgent = BrowserAgent::fromCurrentBrowserAgent();

        $query = DB::queryFirstRow('SELECT session_key FROM sessions ' .
            'WHERE account_id = %s AND ip_address = %s AND browser_agent = %i AND ' .
            'session_key = %s AND expires > NOW() ',
            $accountId, $ipAddress, $browserAgent->getId(), $sessionKey);

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
     * @param BrowserAgent $browserAgent
     * @param string $ip IP of the session
     * @return string Generated session key
     */
    private static function getEncryptedSessionKey(string $accountId, BrowserAgent $browserAgent, string $ip): string
    {
        $salt = Utils::generateRandomString(SESSION_SALT_LENGTH);
        return sha1($salt . time() . $accountId . $browserAgent . $ip);
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