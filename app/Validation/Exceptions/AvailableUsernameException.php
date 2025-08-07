<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class AvailableUsernameException extends ValidationException
{
    public $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Username already exists',
        ]
    ];
}
