<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Models\LoginService;

final class LoginServiceTest extends TestCase
{
    private static $userId;
    private static $password;
    private static $fakePassword;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        \Pulse\Database::init();
        LoginService::setTestEnvironment();
        LoginServiceTest::$userId = "pTest";
        LoginServiceTest::$password = "password";
        LoginServiceTest::$fakePassword = "fakePassword";
        //LoginServiceTest::deleteDatabaseEntries();
    }

    public static function deleteDatabaseEntries()
    {
        DB::delete('user_credentials', "user_id = %s", LoginServiceTest::$userId);
    }

    /**
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueWithoutSigningIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueWithoutSigningIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogInWithoutSigningIn()
    {
        $this->expectException(\Pulse\Exceptions\UserNotExistException::class);
        LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToLogInWithoutSigningIn
     * @throws \Pulse\Exceptions\UserNotExistException
     * @throws \Pulse\Exceptions\UserAlreadyExistsException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testTryToSignIn()
    {
        $session = LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToSignIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueAfterSigningIn()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterSigningIn
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Exceptions\UserAlreadyExistsException
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToSignInAfterSigningIn()
    {
        $this->expectException(AlreadyLoggedInException::class);
        LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToSignInAfterSigningIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToSignOutAfterSignIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToSignOutAfterSignIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogInAfterSigningInWithFakePassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$fakePassword);
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogInAfterSigningInWithFakePassword
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueAfterFakeLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueAfterFakeLogIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogInAfterSigningInWithCorrectPassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToLogInAfterSigningInWithCorrectPassword
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueAfterCorrectLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterCorrectLogIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToSignOutAfterLogIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToSignOutAfterLogIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToSignOutAfterSignOut()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }
}
