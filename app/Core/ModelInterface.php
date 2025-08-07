<?php

namespace App\Core;

use Ramsey\Uuid\UuidInterface;

interface ModelInterface
{
    public function all(): array;
    public function find(UuidInterface $id): ?array;
    public function create(array $data): bool;
    public function update(UuidInterface $id, array $data): bool;
    public function delete(UuidInterface $id): bool;
}