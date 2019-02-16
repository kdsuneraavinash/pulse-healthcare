<?php declare(strict_types=1);

use Pulse\Database;
use Pulse\Exceptions;
use Pulse\Models\User\Credentials;
use PHPUnit\Framework\TestCase;

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
        CredentialsTest::$userId = "pTest";
        CredentialsTest::$fakeId = "testUser123";
        CredentialsTest::$userPassword = "password";
        CredentialsTest::$secondPassword = "233.34.56.788";
        CredentialsTest::$fakePassword = "113.34.56.788";
        DB::delete('user_credentials', "user_id = %s", CredentialsTest::$userId);
    }

    /**
     * @throws Exceptions\UserAlreadyExistsException
     * @throws Exceptions\UserNotExistException
     */
    public function testLoginFromNewAccount()
    {
        $credentials = Credentials::fromNewCredentials(CredentialsTest::$userId, CredentialsTest::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromNewAccount
     * @throws Exceptions\UserNotExistException
     */
    public function testLoginFromExisitngAccount()
    {
        $credentials = Credentials::fromExistingCredentials(CredentialsTest::$userId, CredentialsTest::$userPassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertTrue($credentials->authenticate());
    }

    /**
     * @depends testLoginFromExisitngAccount
     * @throws Exceptions\UserNotExistException
     */
    public function testLoginFromFakePassword()
    {
        $credentials = Credentials::fromExistingCredentials(CredentialsTest::$userId, CredentialsTest::$fakePassword);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertFalse($credentials->authenticate());
    }

    /**
     * @depends testLoginFromFakePassword
     * @throws Exceptions\UserAlreadyExistsException
     * @throws Exceptions\UserNotExistException
     */
    public function testLoginFromNewAccountForExistingUser()
    {
        $this->expectException(Exceptions\UserAlreadyExistsException::class);
        Credentials::fromNewCredentials(CredentialsTest::$userId, CredentialsTest::$fakePassword);
    }

    /**
     * @depends testLoginFromFakePassword
     * @throws Exceptions\UserNotExistException
     */
    public function testLoginFromExistingAccountForNonExistingUser()
    {
        $this->expectException(Exceptions\UserNotExistException::class);
        Credentials::fromExistingCredentials(CredentialsTest::$fakeId, CredentialsTest::$userPassword);
    }

}
