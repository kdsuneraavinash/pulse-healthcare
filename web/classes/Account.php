<?php declare(strict_types=1);

abstract class Account
{
    protected $loginCredential;

    function __construct($loginCredential)
    {
        $this->$loginCredential = $loginCredential;
    }
}
