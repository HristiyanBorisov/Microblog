<?php

namespace App\Models;

use App\Core\ModelInterface;
use PDO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User implements ModelInterface
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(
            [
                ':username' => $username
            ]
        );

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM users')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(UuidInterface $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (id, username, password)
                    VALUES (:id, :username, :password)'
                );

        return $stmt->execute(
            [
                ':id' => Uuid::uuid4()->toString(),
                ':username' => $data['username'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]
        );
    }

    public function update(UuidInterface $id, array $data): bool
    {
        return $this->db->query(
            'UPDATE users SET username = :username, password = :password WHERE id = :id'
        )->execute(
            [
                ':username' => $data['username'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':id' => $id->toString()
            ]
        );
    }

    public function delete(UuidInterface $id): bool
    {
        return $this->db->query(
            'DELETE FROM users WHERE id = :id'
        )->execute(
            [
                ':id' => $id->toString()
            ]
        );
    }
}
