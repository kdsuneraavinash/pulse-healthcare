<?php declare(strict_types=1);

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database;
use Pulse\Models\AccountSession\Session;
use Pulse\Models\Exceptions;


final class SessionTest extends TestCase
{
    private static $userId;
    private static $customIP;
    private static $customUserAgent;

    private static $session;
    private static $session2;
    private static $session_pc;
    private static $session_browser;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        self::$userId = "session_tester";
        self::$customIP = "113.59.194.60";

        Database::delete('sessions', "account_id = :account_id",
            array('account_id' => self::$userId));
    }

    public static function toSession($session): Session
    {
        return $session;
    }

    /**
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testCreateSession()
    {
        self::$session = Session::createSession(self::$userId);
        $this->assertInstanceOf(Session::class, self::$session);

        $query = self::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCreateSession
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testResumeSession()
    {
        self::$session2 = Session::resumeSession(self::$userId, self::getSession()->getSessionKey());
        $this->assertNotNull(self::$session2);

        $query = self::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testResumeSession
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testCreateAnotherSession()
    {
        self::$session = Session::createSession(self::$userId);
        $query = self::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCreateAnotherSession
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testCreateSessionFromAnotherIP()
    {
        $_SERVER['REMOTE_ADDR'] = self::$customIP;
        self::$session_pc = Session::createSession(self::$userId);
        $query = self::getSessions();
        $this->assertCount(2, $query);
        unset($_SERVER['REMOTE_ADDR']);
    }

    /**
     * @depends testCreateSessionFromAnotherIP
     */
    public function testCloseFirstSession()
    {
        self::getSession()->closeSession();
        $query = self::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCloseFirstSession
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testAnotherSessionTriedToResumeSession()
    {
        $session4 = Session::resumeSession(self::$userId, self::getSession()->getSessionKey());
        $this->assertNull($session4);
    }

    /**
     * @depends testAnotherSessionTriedToResumeSession
     */
    public function testSecondSessionTriedToCloseSession()
    {
        self::getSession2()->closeSession();
        $query = self::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testSecondSessionTriedToCloseSession
     */
    public function testCloseSessionFromAnotherIP()
    {
        $_SERVER['HTTP_USER_AGENT'] = self::$customUserAgent;
        self::getSessionPc()->closeSession();
        $query = self::getSessions();
        $this->assertCount(0, $query);
        unset($_SERVER['REMOTE_ADDR']);
    }

    private function getSessions()
    {
        return self::getSessionsOfUser(self::$userId);
    }

    private static function getSessionsOfUser($id)
    {
        return Database::query("SELECT * from sessions WHERE account_id=:account_id",
            array('account_id' => $id));
    }

    public static function getSession(): Session
    {
        return self::$session;
    }

    public static function getSession2(): Session
    {
        return self::$session2;
    }

    public static function getSessionPc(): Session
    {
        return self::$session_pc;
    }

    public static function getSessionBrowser(): Session
    {
        return self::$session_browser;
    }


}
