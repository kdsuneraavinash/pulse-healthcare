<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/7/19
 * Time: 1:34 AM
 */

declare(strict_types=1);

namespace Pulse\Models\AccountSession;
use Pulse\Models\Enums\VerificationState;
use Pulse\Models\AccountSession\AbstractVerificationState;

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