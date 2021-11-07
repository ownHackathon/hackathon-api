<?php declare(strict_types=1);

namespace App\Model;

class Topic
{
    protected int $id;
    protected ?int $eventId;
    protected string $topic;
    protected ?string $description;
    protected ?bool $accepted;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(?int $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

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

    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(null|bool|int $accepted): self
    {
        $this->accepted = (bool)$accepted ?? null;

        return $this;
    }
}
