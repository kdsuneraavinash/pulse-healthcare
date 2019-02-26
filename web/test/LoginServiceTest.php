<?php declare(strict_types=1);

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Models\AccountSession\LoginService;
use Pulse\Models\Exceptions;


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
        \Pulse\Components\Database::init();
        LoginService::setTestEnvironment();
        LoginServiceTest::$userId = "login_service_tester";
        LoginServiceTest::$password = "password";
        LoginServiceTest::$fakePassword = "fakePassword";
        DB::delete('account_credentials', "account_id = %s", LoginServiceTest::$userId);
    }

    /**
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToContinueWithoutSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueWithoutSigningUp
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogInWithoutSigningUp()
    {
        $this->expectException(Exceptions\AccountNotExistException::class);
        LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToLogInWithoutSigningUp
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\AlreadyLoggedInException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToSignUp()
    {
        $session = LoginService::signUpSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToSignUp
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterSigningUp
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\AlreadyLoggedInException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToSignUpAfterSigningUp()
    {
        $this->expectException(Exceptions\AlreadyLoggedInException::class);
        LoginService::signUpSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToSignUpAfterSigningUp
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterSignUp()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterSignUp
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogInWithFakePassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$fakePassword);
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogInWithFakePassword
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterFakeLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueAfterFakeLogIn
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogInWithCorrectPassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToLogInWithCorrectPassword
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterCorrectLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterCorrectLogIn
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterLogIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterLogIn
     * @throws Exceptions\AccountNotExistException
     * @throws Exceptions\AccountRejectedException
     * @throws Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterLogOut()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }
}
