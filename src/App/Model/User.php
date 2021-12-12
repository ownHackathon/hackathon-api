<?php declare(strict_types=1);

namespace App\Model;

use DateTime;

class User
{
    public const USER_ATTRIBUTE = 'loggedInUser';

    protected int $id = 0;
    protected int $roleId = 0;
    protected string $name = '';
    protected string $password = '';
    protected ?string $email = null;
    protected DateTime $registrationTime;
    protected ?DateTime $lastLogin = null;
    protected bool $active = false;

    public function __construct()
    {
        $this->registrationTime = new DateTime();
    }

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

    public function getRegistrationTime(): DateTime
    {
        return $this->registrationTime;
    }

    public function setRegistrationTime(DateTime $registrationTime): self
    {
        $this->registrationTime = $registrationTime;

        return $this;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function isActive(): bool
    {
        return (bool)$this->active;
    }

    public function setActive(int|bool $active): self
    {
        $this->active = (bool)$active;

        return $this;
    }
}
