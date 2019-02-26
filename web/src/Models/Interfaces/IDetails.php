<?php declare(strict_types=1);

namespace Pulse\Models\Interfaces;

/**
 * Interface IDetails
 * Template for details classes
 * @package Pulse\Models\Interfaces
 */
interface IDetails
{

    public function validate();

    public static function readFromDatabase(string $accountId);

    public function saveInDatabase(string $accountId);
}