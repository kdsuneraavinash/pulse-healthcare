<?php

abstract class Account{
    protected $account_ID;
    protected $userName;
    protected $password;
    protected $loginCredential;

    function __construct($loginCredential){
        $this -> $loginCredential = $loginCredential;
    }
    
}



?>