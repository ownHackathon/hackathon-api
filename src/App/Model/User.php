<?php declare(strict_types=1);

namespace App\Model;

class User
{
    protected int $userId;
    protected int $roleId;
    protected string $userName;
    protected string $password;
    protected ?string $email;
    protected string $registrationTime;
    protected ?string $lastLogin;
    protected bool $activ;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

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

    public function isActiv(): bool
    {
        return $this->activ;
    }

    public function setActiv(bool $activ): self
    {
        $this->activ = $activ;

        return $this;
    }
}
