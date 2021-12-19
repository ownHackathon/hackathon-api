<?php declare(strict_types=1);

namespace App\Model;

use DateTime;

class Event
{
    protected int $id = 0;
    protected int $userId = 0;
    protected string $name = '';
    protected ?string $description = null;
    protected string $eventText = '';
    protected DateTime $createTime;
    protected DateTime $startTime;
    protected int $duration = 0;
    protected bool $active = false;
    protected bool $ratingCompleted = false;

    public function __construct()
    {
        $this->createTime = new DateTime();
        $this->startTime = new DateTime();
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

    public function getCreateTime(): DateTime
    {
        return $this->createTime;
    }

    public function setCreateTime(DateTime $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

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

    public function isRatingCompleted(): bool
    {
        return $this->ratingCompleted;
    }

    public function setRatingCompleted(int|bool $ratingCompleted): self
    {
        $this->ratingCompleted = (bool)$ratingCompleted;

        return $this;
    }
}
