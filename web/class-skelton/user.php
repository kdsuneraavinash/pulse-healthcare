<?php include "account.php";?>
<?php

abstract class User extends Account{
    protected $firstName;
    protected $lastName;
    protected $age;
    protected $gender;
    
    public function __construct($firstName,$lastName,$age,$gender){
        $this->firstName=$firstName;
        $this->lastName=$lastName;
        $this->age=$age;
        $this->gender=$gender;
    }


}


?>