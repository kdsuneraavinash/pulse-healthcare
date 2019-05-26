<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/7/19
 * Time: 1:34 AM
 */

declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;
use Pulse\Models\Enums\VerificationState;

class RejectedState extends AbstractVerificationState{
    private $status;

    /**
     * RejectedState constructor.
     */
    public function __construct()
    {
        $this->status = VerificationState::Rejected;
    }

    public function getStatus()
    {
        return $this->status;
    }
}