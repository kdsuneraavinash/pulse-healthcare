<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Models\LoginService;

final class LoginServiceTest extends TestCase
{
    private static $userId;
    private static $password;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        \Pulse\Database::init();
        LoginServiceTest::$userId = "pTest";
        LoginServiceTest::$password = "password";
        LoginServiceTest::deleteDatabaseEntries();
    }

    public static function deleteDatabaseEntries()
    {
    }


    /**
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogIn
     * @throws \Pulse\Exceptions\UserNotExistException
     * @throws \Pulse\Exceptions\UserAlreadyExistsException
     */
    public function testTryToSignIn()
    {
        $session = LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

}
