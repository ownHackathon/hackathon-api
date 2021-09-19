<?php
declare(strict_types=1);

namespace App\Model;

class Participant
{
    protected int $id;
    protected int $userId;
    protected int $eventId;
    protected string $requestTime;
    protected bool $approved;
    protected bool $disqualified;

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

    public function getEventId(): int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getRequestTime(): string
    {
        return $this->requestTime;
    }

    public function setRequestTime(string $requestTime): self
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function isDisqualified(): bool
    {
        return $this->disqualified;
    }

    public function setDisqualified(bool $disqualified): self
    {
        $this->disqualified = $disqualified;

        return $this;
    }
}
