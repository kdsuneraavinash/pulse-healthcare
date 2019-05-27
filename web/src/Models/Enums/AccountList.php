<?php declare(strict_types=1);

namespace Pulse\Models\Enums;

class AccountList extends AccountType{
    const Anyone = [];
    const AnyAccount = [self::Patient, self::Doctor, self::Admin, self::MedicalCenter];
    const DoctorAndPatient = [self::Patient, self::Doctor, self::Admin, self::MedicalCenter];
    const AdminOnly = [self::Admin];
    const DoctorOnly = [self::Doctor];
    const PatientOnly = [self::Patient];
    const MedicalCenterOnly = [self::MedicalCenter];
}