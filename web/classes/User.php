<?php declare(strict_types=1);

include "Account.php";

abstract class User extends Account
{
    protected $firstName;
    protected $lastName;
    protected $age;
    protected $gender;

    public function __construct($firstName, $lastName, $age, $gender, $loginCredentials)
    {
        parent::__construct($loginCredentials);
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->gender = $gender;
    }
}
