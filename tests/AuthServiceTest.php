<?php

use App\Services\AuthService;
use PHPUnit\Framework\TestCase;
use App\Database;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Faker\Factory as Faker;
use Faker\Generator as Generator;

class AuthServiceTest extends TestCase
{
    private $pdo;

    private AuthService $authService;

    private UuidInterface $userId;

    private Generator $faker;

    private string $username;

    private string $password;

    public function setUp(): void
    {
        $this->pdo = Database::connect($_ENV['DB_NAME_TEST']);

        $this->pdo->exec('DELETE FROM users');

        $this->authService = new AuthService($this->pdo);

        $this->createUser();
    }

    public function test_login_success(): void
    {
        $result = $this->authService->attempt($this->username, $this->password);

        $this->assertTrue($result);

        $this->assertArrayHasKey('user', $_SESSION);
    }

    public function test_login_with_invalid_credentials_fail(): void
    {
        $result = $this->authService->attempt($this->username, 'password1');

        $this->assertFalse($result);
    }

    public function test_get_user_success(): void
    {
        $this->authService->attempt($this->username, $this->password);

        $result = $this->authService->user();

        $this->assertArrayHasKey('id', $result);
    }

    public function test_get_user_fail(): void
    {
        $this->authService->attempt($this->username, 'password1');

        $result = $this->authService->user();

        $this->assertNull($result);
    }

    private function createUser(): void
    {
        $this->userId = Uuid::uuid4();

        $this->faker = Faker::create();

        $this->username = $this->faker->userName();

        $this->password = $this->faker->password();

        $stmt = $this->pdo->prepare('INSERT INTO users (id, username, password) VALUES (:id, :username, :password)');
        $stmt->execute([
            ':id' => $this->userId->toString(),
            ':username' => $this->username,
            ':password' => password_hash($this->password, PASSWORD_DEFAULT)
        ]);
    }

}
