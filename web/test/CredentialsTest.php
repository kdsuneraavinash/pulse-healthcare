<?php declare(strict_types=1);

namespace PulseTest;

use PHPUnit\Framework\TestCase;
use Pulse\Components\Database;
use Pulse\Models\AccountSession\Credentials;
use Pulse\Models\Exceptions;


final class CredentialsTest extends TestCase
{
    private static $userId;
    private static $fakeId;
    private static $userPassword;
    private static $secondPassword;
    private static $fakePassword;

    /**
     * @beforeClass
     */
    public static function setSharedVariables()
    {
        Database::init();
        self::$userId = "credentials_tester";
        self::$fakeId = "fakeTestUser123";
        self::$userPassword = "password";
        self::$secondPassword = "233.34.56.788";
        self::$fakePassword = "113.34.56.788";
        Database::delete('account_credentials', "account_id = :account_id",
            array('account_id' => self::$userId));
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromNewAccount()
    {
        $credentials = Credentials::fromNewCredentials(self::$userId, self::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromNewAccount
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromExisitngAccount()
    {
        $credentials = Credentials::fromExistingCredentials(self::$userId, self::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromExisitngAccount
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromFakePassword()
    {
        $credentials = Credentials::fromExistingCredentials(self::$userId, self::$fakePassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertFalse($credentials->authenticate());
    }

    /**
     * @depends testLoginFromFakePassword
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromNewAccountForExistingUser()
    {
        $this->expectException(Exceptions\AccountAlreadyExistsException::class);
        Credentials::fromNewCredentials(self::$userId, self::$fakePassword);
    }

    /**
     * @depends testLoginFromFakePassword
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromExistingAccountForNonExistingUser()
    {
        $this->expectException(Exceptions\AccountNotExistException::class);
        Credentials::fromExistingCredentials(self::$fakeId, self::$userPassword);
    }

}
