<?php declare(strict_types=1);

namespace App\Model;

class User
{
    protected int $id;
    protected int $roleId;
    protected string $name;
    protected string $password;
    protected ?string $email;
    protected string $registrationTime;
    protected ?string $lastLogin;
    protected bool $active;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegistrationTime(): string
    {
        return $this->registrationTime;
    }

    public function setRegistrationTime(string $registrationTime): self
    {
        $this->registrationTime = $registrationTime;

        return $this;
    }

    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?string $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
