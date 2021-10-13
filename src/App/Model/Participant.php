<?php
declare(strict_types=1);

namespace App\Model;

use DateTime;

class Participant
{
    protected int $id;
    protected int $userId;
    protected int $eventId;
    protected DateTime $requestTime;
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

    public function getRequestTime(): DateTime
    {
        return $this->requestTime;
    }

    public function setRequestTime(string $requestTime): self
    {
        $this->requestTime = new DateTime($requestTime);

        return $this;
    }

    public function isApproved(): bool
    {
        return (bool)$this->approved;
    }

    public function setApproved(int|bool $approved): self
    {
        $this->approved = (bool)$approved;

        return $this;
    }

    public function isDisqualified(): bool
    {
        return (bool)$this->disqualified;
    }

    public function setDisqualified(int|bool $disqualified): self
    {
        $this->disqualified = (bool)$disqualified;

        return $this;
    }
}
