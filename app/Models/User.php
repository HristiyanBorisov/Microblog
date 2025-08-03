<?php

namespace App\Models;

use App\Core\ModelInterface;
use PDO;

class User implements ModelInterface
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function find($id){}

    public function findAll(){}

    public function create(array $data){}

    public function update($id, array $data){}

    public function delete($id){}
}