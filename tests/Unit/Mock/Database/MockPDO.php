<?php declare(strict_types=1);

namespace Test\Unit\Mock\Database;

use PDO;

class MockPDO extends PDO
{
    public function __construct()
    {
        parent::__construct('sqlite:');
    }

    public function getAttribute(int $attribute): mixed
    {
        return PDO::ERRMODE_EXCEPTION;
    }
}
