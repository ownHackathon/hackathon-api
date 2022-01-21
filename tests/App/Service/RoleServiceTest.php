<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Role;
use App\Table\RoleTable;

class RoleServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(RoleTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->willReturn(false);

        $service = new RoleService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(RoleTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new RoleService($table, $this->hydrator);

        $role = $service->findById(1);

        $this->assertInstanceOf(Role::class, $role);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(RoleTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new RoleService($table, $this->hydrator);

        $role = $service->findAll();

        $this->assertIsArray($role);
        $this->assertInstanceOf(Role::class, $role[0]);
    }
}
