<?php

namespace App\Models;

use App\Core\ModelInterface;
use PDO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Post implements ModelInterface
{

    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM posts ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(UuidInterface $id): ?array
    {
        return $this->db->query('SELECT * FROM posts WHERE id = :id', ['id' => $id])->fetch(PDO::FETCH_ASSOC)[0];
    }

    public function create(array $data): bool
    {
        return $this->db->query(
            'INSERT INTO posts (id, title, content, image_path) 
                    VALUES (:id, :title, :content, :image_path)
            ')->execute([
                'id' => Uuid::uuid4()->toString(),
                ':title' => $data['title'],
                ':content' => $data['content'],
                ':image_path' => $data['image_path'],
            ]);
    }

    public function update(UuidInterface $id, array $data): bool
    {
        return $this->db->query(
            'UPDATE posts
                    SET title = :title, content = :content, image_path = :image_path, updated_at = :updated_at
                    WHERE id = :id'
            )->execute(
                [
                    ':title' => $data['title'],
                    ':content' => $data['content'],
                    ':image_path' => $data['image_path'],
                    ':updated_at' => (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'),
                    ':id' => $id->toString(),
                ]
            );
    }

    public function delete(UuidInterface $id): bool
    {
        return $this->db->query(
            'DELETE FROM posts WHERE id = :id'
        )->execute(
            [
                ':id' => $id->toString(),
            ]
        );
    }
}
