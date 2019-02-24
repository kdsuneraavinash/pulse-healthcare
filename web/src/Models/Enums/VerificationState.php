<?php declare(strict_types=1);

namespace Pulse\Models\Enums;

class VerificationState
{
    private $state;

    private function __construct(int $state)
    {
        $this->state = $state;
    }

    static function Verified()
    {
        return new VerificationState(1);
    }

    static function Rejected()
    {
        return new VerificationState(2);
    }

    static function Default()
    {
        return new VerificationState(0);
    }

    static function getStateOfInt(int $value){
        if ($value == 0){
            return VerificationState::Default();
        }else  if ($value == 1){
            return VerificationState::Verified();
        }else  if ($value == 2){
            return VerificationState::Rejected();
        }
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function __toString()
    {
        return (string)$this->state;
    }
}