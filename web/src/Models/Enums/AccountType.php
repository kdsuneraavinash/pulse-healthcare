<?php declare(strict_types=1);

namespace Pulse\Models\Enums;

class AccountType
{
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    static function MedicalCenter(){
        return new AccountType('med_center');
    }

    static function Doctor(){
        return new AccountType('doctor');
    }

    static function Patient(){
        return new AccountType('patient');
    }

    static function Tester(){
        return new AccountType('tester');
    }

    static function Admin(){
        return new AccountType('admin');
    }

    public function __toString()
    {
       return $this->type;
    }
}