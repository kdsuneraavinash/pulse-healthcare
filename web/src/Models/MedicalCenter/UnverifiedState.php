<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/6/19
 * Time: 10:30 PM
 */


declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;
use Pulse\Models\Enums\VerificationState;


class UnverifiedState extends AbstractVerificationState {

    private $status;

    /**
     * UnverfiedState constructor.
     */
    public function __construct()
    {
        $this->status = VerificationState::Default;
    }

    public function getStatus()
    {
        return $this->status;
    }


}