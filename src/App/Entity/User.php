<?php declare(strict_types=1);

namespace App\Entity;

use DateTime;

class User
{
    public const USER_ATTRIBUTE = 'authenticatedUser';

    private int $id = 0;
    private string $uuid = '';
    private int $roleId = 0;
    private string $name = '';
    private string $password = '';
    private ?string $email = null;
    private DateTime $registrationTime;
    private ?DateTime $lastAction = null;
    private bool $active = false;
    private ?string $token = null;

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

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

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

    public function getLastAction(): ?DateTime
    {
        return $this->lastAction;
    }

    public function setLastAction(DateTime $lastAction): self
    {
        $this->lastAction = $lastAction;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(int|bool $active): self
    {
        $this->active = (bool)$active;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
