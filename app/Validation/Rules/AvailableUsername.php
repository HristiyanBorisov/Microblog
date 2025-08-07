<?php

namespace App\Validation\Rules;

use App\Models\User;
use Respect\Validation\Rules\Core\Simple;

class AvailableUsername extends Simple
{
    protected User $user;

    public function __construct($container)
    {
        $this->user = new User($container->db);
    }

    public function isValid(mixed $input): bool
    {
        return empty($this->user->findByUsername($input));
    }
}
