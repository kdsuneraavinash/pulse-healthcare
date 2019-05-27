<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/6/19
 * Time: 10:29 PM
 */

declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;

use Pulse\Models\Enums\VerificationState;


class VerifiedState extends AbstractVerificationState
{

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