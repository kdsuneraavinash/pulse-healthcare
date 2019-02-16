<?php declare(strict_types=1);

use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Exceptions\UserNotExistException;
use Pulse\Models\LoginService;
use PHPUnit\Framework\TestCase;

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
        DB::delete('user_credentials', "user_id = %s", LoginServiceTest::$userId);
    }

    /**
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueWithoutSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueWithoutSigningUp
     * @throws UserNotExistException
     */
    public function testTryToLogInWithoutSigningUp()
    {
        $this->expectException(UserNotExistException::class);
        LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToLogInWithoutSigningUp
     * @throws \Pulse\Exceptions\UserNotExistException
     * @throws \Pulse\Exceptions\UserAlreadyExistsException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testTryToSignUp()
    {
        $session = LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToSignUp
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToContinueAfterSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterSigningUp
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Exceptions\UserAlreadyExistsException
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToSignUpAfterSigningUp()
    {
        $this->expectException(AlreadyLoggedInException::class);
        LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToSignUpAfterSigningUp
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogOutAfterSignUp()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterSignUp
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogInWithFakePassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$fakePassword);
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogInWithFakePassword
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
    public function testTryToLogInWithCorrectPassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToLogInWithCorrectPassword
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
    public function testTryToLogOutAfterLogIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterLogIn
     * @throws \Pulse\Exceptions\UserNotExistException
     */
    public function testTryToLogOutAfterLogOut()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }
}
