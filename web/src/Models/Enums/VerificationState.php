<?php declare(strict_types=1);

namespace Pulse\Models\Enums;

class VerificationState
{
    const Verified = 1;
    const Rejected = 2;
    const Default = 0;

    const Values = array(VerificationState::Verified, VerificationState::Rejected, VerificationState::Default);

    public static  function isValid(string $type):bool{
        return array_key_exists($type, AccountType::Values);
    }
}