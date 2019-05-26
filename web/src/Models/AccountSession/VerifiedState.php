<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/6/19
 * Time: 10:29 PM
 */

declare(strict_types=1);

namespace Pulse\Models\AccountSession;
use Pulse\Models\Enums\VerificationState;
use Pulse\Models\AccountSession\AbstractVerificationState;



class VerifiedState extends AbstractVerificationState {

    private $status;

    /**
     * VerifiedState constructor.
     */
    public function __construct()
    {
        $this->status = VerificationState::Verified;
    }

    public function getStatus()
    {

        return $this->status;

    }


}