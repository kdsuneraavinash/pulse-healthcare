<?php

namespace Pulse\MVC;

use DB;

class User{
    private $id;
    private $sessionKey;
    private $logoutKey;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getID() : string{
        return $this->id;
    }

    public function getSessionKey() : string{
        return $this->sessionKey;
    }

    public function getLogoutKey() : string{
        return $this->logoutKey;
    }

    public function setSessionKey(string $sessionKey) {
        $this->sessionKey = $sessionKey;
    }

    public function setLogoutKey(string $logoutKey){
        $this->logoutKey = $logoutKey;
    }

    public function exists() : bool{
        $records = DB::query("SELECT * FROM test WHERE ID=" . $this->id);
        return count($records) == 1;
    }
}