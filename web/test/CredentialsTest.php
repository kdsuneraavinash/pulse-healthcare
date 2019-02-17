<?php declare(strict_types=1);

namespace PulseTest;

use DB;
use PHPUnit\Framework\TestCase;
use Pulse\Database;
use Pulse\Exceptions;
use Pulse\Models\AccountSession\Credentials;

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
        CredentialsTest::$userId = "credentials_tester";
        CredentialsTest::$fakeId = "fakeTestUser123";
        CredentialsTest::$userPassword = "password";
        CredentialsTest::$secondPassword = "233.34.56.788";
        CredentialsTest::$fakePassword = "113.34.56.788";
        DB::delete('account_credentials', "account_id = %s", CredentialsTest::$userId);
    }

    /**
     * @throws Exceptions\AccountAlreadyExistsException
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromNewAccount()
    {
        $credentials = Credentials::fromNewCredentials(CredentialsTest::$userId, CredentialsTest::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromNewAccount
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromExisitngAccount()
    {
        $credentials = Credentials::fromExistingCredentials(CredentialsTest::$userId, CredentialsTest::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromExisitngAccount
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromFakePassword()
    {
        $credentials = Credentials::fromExistingCredentials(CredentialsTest::$userId, CredentialsTest::$fakePassword);
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
        Credentials::fromNewCredentials(CredentialsTest::$userId, CredentialsTest::$fakePassword);
    }

    /**
     * @depends testLoginFromFakePassword
     * @throws Exceptions\AccountNotExistException
     */
    public function testLoginFromExistingAccountForNonExistingUser()
    {
        $this->expectException(Exceptions\AccountNotExistException::class);
        Credentials::fromExistingCredentials(CredentialsTest::$fakeId, CredentialsTest::$userPassword);
    }

}
