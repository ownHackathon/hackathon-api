<?php declare(strict_types=1);

namespace App\Test\Mock;

use PDO;

class MockPDO extends PDO
{
    public function __construct()
    {
        parent::__construct('sqlite:');
    }
}
