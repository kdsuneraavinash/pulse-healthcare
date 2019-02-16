<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Models\AccountSession\Session;

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
        \Pulse\Database::init();
        SessionTest::$userId = "session_tester";
        SessionTest::$customIP = "113.59.194.60";
        SessionTest::$customUserAgent = "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T)" .
            " AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36";
        DB::delete('sessions', "account_id = %s", SessionTest::$userId);
    }

    public static function toSession($session): Session
    {
        return $session;
    }

    /**
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testCreateSession()
    {
        SessionTest::$session = Session::createSession(SessionTest::$userId);
        $this->assertInstanceOf(Session::class, SessionTest::$session);

        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCreateSession
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testResumeSession()
    {
        SessionTest::$session2 = Session::resumeSession(SessionTest::$userId, SessionTest::getSession()->getSessionKey());
        $this->assertNotNull(SessionTest::$session2);

        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testResumeSession
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testCreateAnotherSession()
    {
        SessionTest::$session = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCreateAnotherSession
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testCreateSessionFromAnotherIP()
    {
        $_SERVER['REMOTE_ADDR'] = SessionTest::$customIP;
        SessionTest::$session_pc = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(2, $query);
        unset($_SERVER['REMOTE_ADDR']);
    }

    /**
     * @depends testCreateSessionFromAnotherIP
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testCreateSessionFromAnotherBrowser()
    {
        $_SERVER['HTTP_USER_AGENT'] = SessionTest::$customUserAgent;
        SessionTest::$session_browser = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(3, $query);
    }

    /**
     * @depends testCreateSessionFromAnotherBrowser
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testCreateSessionAgainFromAnotherBrowser()
    {
        Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(3, $query);
    }

    /**
     * @depends testCreateSessionAgainFromAnotherBrowser
     */
    public function testBrowserSessionOfOtherBrowserClosed()
    {
        SessionTest::getSessionBrowser()->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(2, $query);
        unset($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * @depends testBrowserSessionOfOtherBrowserClosed
     */
    public function testCloseFirstSession()
    {
        SessionTest::getSession()->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testCloseFirstSession
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testAnotherSessionTriedToResumeSession()
    {
        $session4 = Session::resumeSession(SessionTest::$userId, SessionTest::getSession()->getSessionKey());
        $this->assertNull($session4);
    }

    /**
     * @depends testAnotherSessionTriedToResumeSession
     */
    public function testSecondSessionTriedToCloseSession()
    {
        SessionTest::getSession2()->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);
    }

    /**
     * @depends testSecondSessionTriedToCloseSession
     */
    public function testCloseSessionFromAnotherIP()
    {
        $_SERVER['HTTP_USER_AGENT'] = SessionTest::$customUserAgent;
        SessionTest::getSessionPc()->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(0, $query);
        unset($_SERVER['REMOTE_ADDR']);
    }

    private function getSessions()
    {
        return SessionTest::getSessionsOfUser(SessionTest::$userId);
    }

    private static function getSessionsOfUser($id)
    {
        return DB::query("SELECT * FROM sessions WHERE account_id = '$id'");
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
