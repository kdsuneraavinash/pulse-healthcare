<?php declare(strict_types=1);

namespace Pulse\Models\Enums;

class AccountType
{
    const MedicalCenter = 'med_center';
    const Doctor = 'doctor';
    const Patient = 'doctor';
    const Tester = 'tester';
    const Admin = 'admin';

    const Values = array(AccountType::MedicalCenter, AccountType::Doctor,
        AccountType::Patient, AccountType::Tester, AccountType::Admin);

    public static  function isValid(string $type):bool{
        return array_key_exists($type, AccountType::Values);
    }
}