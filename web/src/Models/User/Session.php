<?php declare(strict_types=1);

namespace Pulse\Models\User;

use DB;
use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\BaseModel;
use Pulse\Utils;

define('USER_EXPIRATION_DAYS', 1);
define('SALT_LENGTH', 40);


class Session implements BaseModel
{
    private $userId;
    private $sessionKey;

    /**
     * Session constructor.
     * @param string $userId BaseUser id of the user
     * @param string $sessionKey Session key
     * @throws UserNotExistException if user does not exist
     */
    private function __construct(string $userId, string $sessionKey)
    {
        $user = new TestUser($userId);
        if (!$user->exists()) {
            throw new UserNotExistException($userId);
        }

        $this->userId = $userId;
        $this->sessionKey = $sessionKey;
    }

    /**
     * Create a new user session
     * @param string $userId BaseUser ID of the user to create session
     * @return Session Created Session Object
     * @throws UserNotExistException
     */
    public static function createSession(string $userId): Session
    {
        $ipAddress = Utils::getClientIP();
        $userAgent = BrowserUserAgent::fromCurrentUserAgent();
        $sessionKey = Session::getEncryptedSessionKey($userId, $userAgent, $ipAddress);

        $primaryKey = array(
            'user' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent->getId());
        $record = array(
            'created' => DB::sqleval("NOW()"),
            'expires' => DB::sqleval("ADDDATE(NOW(), " . USER_EXPIRATION_DAYS . ")"),
            'session_key' => DB::sqleval("UNHEX('$sessionKey')")
        );

        $query = DB::queryFirstRow('SELECT HEX(session_key) FROM sessions WHERE user=%s AND ip_address=%s AND user_agent=%i',
            $userId, $ipAddress, $userAgent->getId());
        if ($query != null) {
            // Exists: Get existing data - Must Not Update since old session keys will become invalid
            $sessionKey = $query['HEX(session_key)'];
        } else {
            // Does Not Exist: Insert session
            DB::insert('sessions', array_merge($primaryKey, $record));
        }

        return new Session($userId, $sessionKey);
    }

    /**
     * Resumes a user session
     * @param string $userId BaseUser Id to resume session
     * @param string $sessionKey Session Key of the session to resume
     * @return Session|null Created session(null if session key is invalid)
     * @throws UserNotExistException
     */
    public static function resumeSession(string $userId, string $sessionKey): ?Session
    {
        $ipAddress = Utils::getClientIP();
        $userAgent = BrowserUserAgent::fromCurrentUserAgent();

        $query = DB::queryFirstRow('SELECT session_key FROM sessions ' .
            'WHERE user = %s AND ip_address = %s AND user_agent = %i AND ' .
            'session_key = UNHEX(%s) AND expires > NOW() ',
            $userId, $ipAddress, $userAgent->getId(), $sessionKey);

        // Return null if session didn't exist
        if ($query == null) {
            return null;
        }

        return new Session($userId, $sessionKey);
    }

    /**
     * Close a user session
     */
    public function closeSession()
    {
        Session::closeSessionOfContext($this->userId, $this->sessionKey);
    }

    /**
     * Close a user session
     * @param string $id
     * @param string $sessionKey
     */
    public static function closeSessionOfContext(string $id, string $sessionKey)
    {
        DB::delete('sessions', "user = %s AND session_key = UNHEX(%s)", $id, $sessionKey);
    }

    /**
     * Generate a session key using a user-defined salt
     * @param string $userId BaseUser id of the user to generate session key
     * @param BrowserUserAgent $userAgent BaseUser agent of the session
     * @param string $ip IP of the session
     * @return string Generated session key
     */
    private static function getEncryptedSessionKey(string $userId, BrowserUserAgent $userAgent, string $ip): string
    {
        $salt = Utils::generateRandomString(SALT_LENGTH);;
        return sha1($salt . time() . $userId . $userAgent . $ip);
    }

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }
}