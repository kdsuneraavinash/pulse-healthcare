<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Framework\Session;

final class SessionTest extends TestCase
{
    private static $userId;
    private static $customIP;
    private static $customUserAgent;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {

        DB::$user = 'pulse_root';
        DB::$password = 'password';
        DB::$dbName = 'pulse';
        DB::$host = 'localhost';
        DB::$port = '3306';
        DB::$encoding = 'latin1';
        SessionTest::$userId = "pTest";
        SessionTest::$customIP = "113.59.194.60";
        SessionTest::$customUserAgent = "Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T)" .
            " AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Mobile Safari/537.36";
        SessionTest::deleteDatabaseEntries();
    }

    public static function deleteDatabaseEntries()
    {
        DB::delete('sessions', "user = %s", SessionTest::getUserId());
    }

    private static function getUserId(): string
    {
        return SessionTest::$userId;
    }

    /**
     * --------------------------------------------
     * Basic
     * --------------------------------------------
     */

    public function testCreateSessionObject()
    {
        $session = Session::createSession(SessionTest::$userId);
        $this->assertInstanceOf(Session::class, $session);
        $session->closeSession();
    }

    /**
     * --------------------------------------------
     * Creating And Closing Sessions
     * --------------------------------------------
     */

    public function testSessionEmulation()
    {
        /// Created Session
        $session = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);

        /// Another Session Resumed it
        $session2 = Session::resumeSession(SessionTest::$userId, $session->getSessionKey());
        $this->assertNotNull($session2);

        /// Another Session Created it
        $session = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);

        /// Logged in from another Computer
        $_SERVER['REMOTE_ADDR'] = SessionTest::$customIP;
        $session_pc = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(2, $query);
        unset($_SERVER['REMOTE_ADDR']);

        /// Logged in from another Browser
        $_SERVER['HTTP_USER_AGENT'] = SessionTest::$customUserAgent;
        $session_browser = Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(3, $query);

        /// Again logged in from another Browser
        Session::createSession(SessionTest::$userId);
        $query = SessionTest::getSessions();
        $this->assertCount(3, $query);

        /// Another Browser Session Closed
        $session_browser->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(2, $query);
        unset($_SERVER['HTTP_USER_AGENT']);

        /// First Session Closed
        $session->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);

        /// Another Session tried to resume it
        $session4 = Session::resumeSession(SessionTest::$userId, $session->getSessionKey());
        $this->assertNull($session4);

        /// Second session tried to close it
        $session2->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(1, $query);

        /// Another IP Session Closed
        $_SERVER['HTTP_USER_AGENT'] = SessionTest::$customUserAgent;
        $session_pc->closeSession();
        $query = SessionTest::getSessions();
        $this->assertCount(0, $query);
        unset($_SERVER['REMOTE_ADDR']);
    }

    private function getSessions()
    {
        return SessionTest::getSessionsOfUser(SessionTest::getUserId());
    }

    private static function getSessionsOfUser($id)
    {
        return DB::query("SELECT * FROM sessions WHERE user = '$id'");
    }
}
