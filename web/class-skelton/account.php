<?php

abstract class Account{
    protected $loginCredential;

    function __construct($loginCredential){
        $this -> $loginCredential = $loginCredential;
    }
    
}



?>