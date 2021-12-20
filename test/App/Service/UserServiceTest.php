<?php declare(strict_types=1);

namespace App\Service;

use App\Model\User;
use App\Table\UserTable;
use Laminas\Hydrator\ReflectionHydrator;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        $userTable = $this->createMock(UserTable::class);
        $hydrator = $this->createMock(ReflectionHydrator::class);

        $this->service = new UserService($userTable, $hydrator);

        parent::setUp();
    }

    public function testCanCreateUser()
    {
        $user = new User();

        $insert = $this->service->create($user);

        $this->assertSame(true, $insert);
    }
}
