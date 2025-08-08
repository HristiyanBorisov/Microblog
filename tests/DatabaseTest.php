<?php

namespace tests;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function test_database_connection_success()
    {
        $pdo = Database::connect($_ENV['DB_NAME_TEST']);
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function test_database_connection_has_correct_attributes_success()
    {
        $pdo = Database::connect($_ENV['DB_NAME_TEST']);
        $this->assertEquals(PDO::ERRMODE_EXCEPTION, $pdo->getAttribute(PDO::ATTR_ERRMODE));
        $this->assertEquals(PDO::FETCH_ASSOC, $pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE));
    }
}
