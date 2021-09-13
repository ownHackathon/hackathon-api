<?php declare(strict_types=1);

namespace App\Model;

class Event
{
    protected int $id;
    protected int $userId;
    protected string $name;
    protected ?string $description;
    protected string $eventText;
    protected string $createTime;
    protected string $startTime;
    protected string $duration;
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

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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

    public function getEventText(): string
    {
        return $this->eventText;
    }

    public function setEventText(string $eventText): self
    {
        $this->eventText = $eventText;

        return $this;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function setStartTime(string $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

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
