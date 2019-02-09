<?php

namespace Pulse\MVC;

use Pulse\MVC;
use DB;

define('USER_EXPIRATION_DAYS', 1);

define('SESSIONS_TABLE', 'sessions');

define('USER_FIELD', 'user');
define('CREATED_FIELD', 'created');
define('EXPIRES_FIELD', 'expires');
define('SESSION_KEY_FIELD', 'session_key');
define('LOGOUT_KEY_FIELD', 'logout_key');


class Session
{
    private static $salts;

    // Create a new user session
    public static function create(MVC\User $user): MVC\User
    {
        $userId = $user->getID();
        $sessionKey = self::generateSessionKey($userId);
        $logoutKey = self::generateLogoutKey($userId);

        // Insert
        DB::insert(SESSIONS_TABLE, array(
            USER_FIELD => $userId,
            CREATED_FIELD => DB::sqleval("NOW()"),
            EXPIRES_FIELD => DB::sqleval("ADDDATE(NOW(), " . USER_EXPIRATION_DAYS . ")"),
            SESSION_KEY_FIELD => DB::sqleval("UNHEX('$sessionKey')"),
            LOGOUT_KEY_FIELD => DB::sqleval("UNHEX('$logoutKey')"),
        ));

        // Update user object with keys
        $user->setSessionKey($sessionKey);
        $user->setLogoutKey($logoutKey);
        return $user;
    }

    // Set salts for session key and logout key
    public static function setSalts($sessionKey, $logoutKey)
    {
        self::$salts = array(SESSION_KEY_FIELD => $sessionKey, LOGOUT_KEY_FIELD => $logoutKey);
    }

    // Generate a session key using a user-defined salt
    private static function generateSessionKey($userId)
    {
        return sha1(self::$salts[SESSION_KEY_FIELD] . time() . $userId);
    }

    // Generate a logout key using a user-defined salt
    private static function generateLogoutKey($userId)
    {
        return sha1(self::$salts[LOGOUT_KEY_FIELD] . time() . $userId);
    }

    // Resume a user session
    public static function resume($userId, $sessionKey)
    {
        // Check a session exists
        if (!$session = self::exists($userId, $sessionKey)) {
            return false;
        }
        // Create a user instance
        $user = new User($userId);
        // Return false if user not found
        if (!($user->exists())) {
            return false;
        }
        // Set session details with user
        $user->setSessionKey($session[SESSION_KEY_FIELD]);
        $user->setLogoutKey($session[LOGOUT_KEY_FIELD]);
        return $user;
    }

    // Close a user session
    public static function close(User $user)
    {
        // Prepare params
        $userId = $user->getID();
        $sessionKey = $user->getSessionKey();
        $logoutKey = $user->getLogoutKey();

        // TODO: Database Actions - Prepare delete query
        // $delete = Db::prepare('DELETE FROM sessions WHERE user = ? AND session_key = ? AND logout_key = ? LIMIT 1');
        // $delete->bind_param('iss', $userId, $sessionKey, $logoutKey);
        // $delete->execute();
        // $rows = $delete->affected_rows;
        //$ delete->close();

        // If no session found return false
        // Shouldn't happen unless this method allowed to be called by non-authed users
        // if ($rows == 0) {
        //     return false;
        // }

        return true;
    }

    // Check whether the session exists
    private static function exists($userId, $sessionKey)
    {
        // TODO: Database - Prepare check query
        // $query = 'SELECT session_key, logout_key FROM sessions WHERE user = ? AND session_key = ? AND expires > NOW() LIMIT 1';
        // $check = Db::prepare($query);
        // $check->bind_param('is', $userId, $sessionKey);
        // $check->execute();
        // $check->store_result();
        // Return false if session didn't exist
        // if ($check->num_rows == 0) {
        //     $check->close();
        //     return false;
        // }

        // Return session and logout keys
        $return = array();

//        $check->bind_result($return['session_key'], $return['logout_key']);
//        $check->fetch();
//        $check->close();

        return $return;
    }
}