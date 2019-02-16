<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Pulse\Exceptions\AccountNotExistException;
use Pulse\Exceptions\AlreadyLoggedInException;
use Pulse\Models\AccountSession\LoginService;

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
        LoginServiceTest::$userId = "login_service_tester";
        LoginServiceTest::$password = "password";
        LoginServiceTest::$fakePassword = "fakePassword";
        DB::delete('account_credentials', "account_id = %s", LoginServiceTest::$userId);
    }

    /**
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToContinueWithoutSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueWithoutSigningUp
     * @throws AccountNotExistException
     */
    public function testTryToLogInWithoutSigningUp()
    {
        $this->expectException(AccountNotExistException::class);
        LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToLogInWithoutSigningUp
     * @throws \Pulse\Exceptions\AccountNotExistException
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     */
    public function testTryToSignUp()
    {
        $session = LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToSignUp
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToContinueAfterSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterSigningUp
     * @throws \Pulse\Exceptions\AlreadyLoggedInException
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToSignUpAfterSigningUp()
    {
        $this->expectException(AlreadyLoggedInException::class);
        LoginService::signInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToSignUpAfterSigningUp
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToLogOutAfterSignUp()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterSignUp
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToLogInWithFakePassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$fakePassword);
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogInWithFakePassword
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToContinueAfterFakeLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueAfterFakeLogIn
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToLogInWithCorrectPassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToLogInWithCorrectPassword
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToContinueAfterCorrectLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterCorrectLogIn
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToLogOutAfterLogIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterLogIn
     * @throws \Pulse\Exceptions\AccountNotExistException
     */
    public function testTryToLogOutAfterLogOut()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }
}
