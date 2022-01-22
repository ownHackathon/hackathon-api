<?php declare(strict_types=1);

namespace App\Model;

class Role
{
    public const ADMINISTRATOR = 1;
    public const MODERATOR = 2;
    public const USER = 3;

    private int $id = 0;
    private string $name = '';
    private ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
