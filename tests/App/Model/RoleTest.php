<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    private Role $role;

    protected function setUp(): void
    {
        $this->role = new Role();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull()
    {
        $description = $this->role->getDescription();

        $this->assertNull($description);
    }

    public function testCanSetAndGetId()
    {
        $roleId = $this->role->setId(1);
        $id = $roleId->getId();

        $this->assertInstanceOf(Role::class, $roleId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetName()
    {
        $roleName = $this->role->setName('test');
        $name = $roleName->getName();

        $this->assertInstanceOf(Role::class, $roleName);
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetDescription()
    {
        $roleDescription = $this->role->setDescription('test');
        $description = $roleDescription->getDescription();

        $this->assertInstanceOf(Role::class, $roleDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }
}
