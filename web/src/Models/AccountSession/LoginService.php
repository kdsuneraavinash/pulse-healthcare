<?php

namespace Pulse\Models\AccountSession;

use Pulse\Components\Database;
use Pulse\Definitions;
use Pulse\Models\BaseModel;
use Pulse\Models\Exceptions;

class LoginService implements BaseModel
{
    private static $testFlag = false;

    /**
     * Continue the previous session (by using cookies)
     * @return Session|null
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public static function continueSession(): ?Session
    {
        if (self::sessionCookiesExist()) {
            // Cookies are present
            $sessionUser = self::getSessionUser();
            $sessionKey = self::getSessionKey();

            $session = Session::resumeSession($sessionUser, $sessionKey);

            if ($session == null) {
                // Session key invalid: delete cookie
                self::deleteSession();
                return null;
            } else {
                // Session key valid: LOGIN
                return $session;
            }
        } else {
            return null;
        }
    }

    /**
     * Create session and login
     * @param string $accountId
     * @param string $password
     * @return Session|null
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public static function logInSession(string $accountId, string $password): ?Session
    {
        // Delete previous cookies
        self::deleteSession();

        // Verify correct password and authenticate
        $credentials = Credentials::fromExistingCredentials($accountId, $password);
        if (!$credentials->authenticate()) {
            // Invalid credentials
            return null;
        }

        $session = Session::createSession($accountId);
        self::saveCookie($session);
        return $session;
    }


    /**
     * Try to sign up - create passwords and save them
     * @param string $accountId
     * @param string $password
     * @return Session
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\AlreadyLoggedInException
     * @throws Exceptions\InvalidDataException
     */
    public static function signUpSession(string $accountId, string $password): Session
    {
        $currentSession = self::continueSession();
        if ($currentSession != null) {
            throw new Exceptions\AlreadyLoggedInException($accountId);
        }

        // Delete previous cookies
        self::deleteSession();

        // Verify correct password and authenticate
        self::createNewCredentials($accountId, $password);

        $session = Session::createSession($accountId);
        self::saveCookie($session);
        return $session;
    }

    /**
     * @param string $accountId
     * @param string $password
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     */
    public static function createNewCredentials(string $accountId, string $password)
    {
        Credentials::fromNewCredentials($accountId, $password);
    }


    /**
     */
    public static function signOutSession()
    {
        self::deleteSession();
    }

    /**
     * @return string
     */
    private static function getSessionUser(): ?string
    {
        if (!isset($_COOKIE[Definitions::SESSION_USER])) {
            return null;
        }
        return $_COOKIE[Definitions::SESSION_USER];
    }

    /**
     * @return string
     */
    private static function getSessionKey(): ?string
    {
        if (!isset($_COOKIE[Definitions::SESSION_KEY])) {
            return null;
        }
        return $_COOKIE[Definitions::SESSION_KEY];
    }

    /**
     * @param Session $session
     */
    private static function saveCookie(Session $session)
    {
        self::setCookie(Definitions::SESSION_USER, $session->getSessionAccount()->getAccountId());
        self::setCookie(Definitions::SESSION_KEY, $session->getSessionKey());
    }

    /**
     * @return bool
     */
    private static function sessionCookiesExist(): bool
    {
        return isset($_COOKIE[Definitions::SESSION_KEY]) and isset($_COOKIE[Definitions::SESSION_USER]);
    }

    private static function deleteSession()
    {
        // Detele session details from DB and cookies
        Database::delete('sessions',
            'account_id=:account_id',
            array('account_id' => self::getSessionUser()));
        self::deleteCookie(Definitions::SESSION_USER);
        self::deleteCookie(Definitions::SESSION_KEY);
    }

    /**
     * @param string $name
     */
    private static function deleteCookie(string $name)
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
        if (!self::$testFlag) {
            setcookie($name, '', time() - 3600, '/');
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    private static function setCookie(string $name, string $value)
    {
        if (self::$testFlag) {
            $_COOKIE[$name] = $value;
        } else {
            setcookie($name, $value, time() + (Definitions::SECONDS_PER_DAY * Definitions::COOKIE_VALID_PERIOD_DAYS), "/");
        }
    }

    /**
     * For testing purposes - to emulate cookies
     */
    public static function setTestEnvironment()
    {
        self::$testFlag = true;
    }
}