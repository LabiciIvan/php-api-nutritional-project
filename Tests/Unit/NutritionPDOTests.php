<?php

namespace Tests\Unit;

use PDO;
use App\Database\NutritionPDO;
use PHPUnit\Framework\TestCase;

class NutritionPDOTests extends TestCase
{
    private NutritionPDO $db;

    public function setUp(): void
    {
        $this->db = NutritionPDO::getInstance();
    }

    public function testConnectionToDatabase(): void
    {
        $this->assertInstanceOf(PDO::class, $this->db);
    }

    public function testFetchDataFromUsersTable(): void
    {
        $query = "SELECT * FROM users ORDER BY id DESC LIMIT 1";

        $pdoStatement = $this->db->query($query, PDO::FETCH_ASSOC);

        $queryResult = $pdoStatement->fetch();

        $this->assertIsArray($queryResult);
    }

    public function testQuickFetch(): void
    {
        $result = $this->db->quickFetch("SELECT * FROM users");

        $this->assertNotNull($result);

        $this->assertIsArray($result);
    }

    public function testRunQuery(): void
    {
        $valuesToInsert = ['Binary', 'Hexa', uniqid('dummyEmail'), 'man'];

        $result = $this->db->runQuery(
            "INSERT INTO users (first_name, last_name, email, gender) VALUE (?, ?, ?, ?)",
            $valuesToInsert
        );

        $this->assertTrue($result);
    }
}
