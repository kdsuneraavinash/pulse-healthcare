<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/6/19
 * Time: 10:58 PM
 */

declare(strict_types=1);

namespace Pulse\Models\MedicalCenter;


abstract class AbstractVerificationState{

    abstract function getStatus();
}