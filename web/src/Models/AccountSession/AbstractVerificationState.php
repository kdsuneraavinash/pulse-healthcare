<?php
/**
 * Created by PhpStorm.
 * User: lahiru
 * Date: 5/6/19
 * Time: 10:58 PM
 */

declare(strict_types=1);

namespace Pulse\Models\AccountSession;


abstract class AbstractVerificationState{

    abstract function getStatus();

}