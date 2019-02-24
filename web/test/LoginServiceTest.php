<?php declare(strict_types=1);

namespace PulseTest;

use DB;
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
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToContinueWithoutSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueWithoutSigningUp
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogInWithoutSigningUp()
    {
        $this->expectException(AccountNotExistException::class);
        LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToLogInWithoutSigningUp
     * @throws AccountNotExistException
     * @throws AlreadyLoggedInException
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToSignUp()
    {
        $session = LoginService::signUpSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToSignUp
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterSigningUp()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterSigningUp
     * @throws AccountNotExistException
     * @throws AlreadyLoggedInException
     * @throws \Pulse\Exceptions\AccountAlreadyExistsException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToSignUpAfterSigningUp()
    {
        $this->expectException(AlreadyLoggedInException::class);
        LoginService::signUpSession(LoginServiceTest::$userId, LoginServiceTest::$password);
    }

    /**
     * @depends testTryToSignUpAfterSigningUp
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterSignUp()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterSignUp
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogInWithFakePassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$fakePassword);
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogInWithFakePassword
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterFakeLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToContinueAfterFakeLogIn
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogInWithCorrectPassword()
    {
        $session = LoginService::logInSession(LoginServiceTest::$userId, LoginServiceTest::$password);
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToLogInWithCorrectPassword
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToContinueAfterCorrectLogIn()
    {
        $session = LoginService::continueSession();
        $this->assertNotNull($session);
    }

    /**
     * @depends testTryToContinueAfterCorrectLogIn
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterLogIn()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }

    /**
     * @depends testTryToLogOutAfterLogIn
     * @throws AccountNotExistException
     * @throws \Pulse\Exceptions\AccountRejectedException
     * @throws \Pulse\Exceptions\InvalidDataException
     */
    public function testTryToLogOutAfterLogOut()
    {
        LoginService::signOutSession();
        $session = LoginService::continueSession();
        $this->assertNull($session);
    }
}
