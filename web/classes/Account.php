<?php declare(strict_types=1);

abstract class Account
{
    protected $fax;

    function __construct($loginCredential)
    {
        $this->$loginCredential = $loginCredential;
    }
}
