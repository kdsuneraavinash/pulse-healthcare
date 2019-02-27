<?php declare(strict_types=1);

namespace Pulse;

class Definitions
{
    const TEMPLATES = __DIR__ . '/Views';
    const CACHE = __DIR__ . '/../cache';

    const PEPPER = '14a5168782azxa5b4648de2chjufcb3afed6drt4';
    const CREDENTIALS_SALT_LENGTH = 40;

    const USER_EXPIRATION_DAYS = 1;
    const SESSION_SALT_LENGTH = 40;

    const SECONDS_PER_DAY = 86400;
    const COOKIE_VALID_PERIOD_DAYS = 7;
    const SESSION_USER = 'session_user';
    const SESSION_KEY = 'session_key';
}
