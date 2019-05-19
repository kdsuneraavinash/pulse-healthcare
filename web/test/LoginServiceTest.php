<?php declare(strict_types=1);

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database;
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
        LoginService::setTestEnvironment();
        self::$userId = "login_service_tester";
        self::$password = "password";
        self::$fakePassword = "fakePassword";
        Database::delete('account_credentials', "account_id = :account_id",
            array('account_id' => self::$userId));
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
        LoginService::logInSession(self::$userId, self::$password);
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
        $session = LoginService::signUpSession(self::$userId, self::$password);
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
        LoginService::signUpSession(self::$userId, self::$password);
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
        $session = LoginService::logInSession(self::$userId, self::$fakePassword);
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
        $session = LoginService::logInSession(self::$userId, self::$password);
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
