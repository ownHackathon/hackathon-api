<?php declare(strict_types=1);

namespace App\Table;

/**
 * @property RoleTable $table
 */
class RoleTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $role = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $role);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $role = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $role);
    }
}
