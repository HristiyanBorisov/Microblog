<?php

namespace App\Services;

use App\Core\ServiceInterface;
use App\Models\User;
use PDO;
use Ramsey\Uuid\Uuid;

class AuthService implements ServiceInterface
{
    protected User $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
    }

    public function execute(array $data): bool
    {
        return $this->attempt($data['username'], $data['password']);
    }

    public function attempt(string $username, string $password): bool
    {
        $user = $this->userModel->findByUsername($username);

        if ($user['password'] && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            return true;
        }
        return false;
    }

    public function user(): ?array
    {
        return $_SESSION['user'] ? $this->userModel->find(Uuid::fromString($_SESSION['user'])) : null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }
}
