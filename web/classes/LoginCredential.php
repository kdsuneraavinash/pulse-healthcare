<?php declare(strict_types=1);

class LoginCredential
{
    private $accountID;
    private $username;
    private $password;

    public function __construct($accountID, $username, $password)
    {
        $this->accountID = $accountID;
        $this->username = $username;
        $this->password = $password;
    }
}
