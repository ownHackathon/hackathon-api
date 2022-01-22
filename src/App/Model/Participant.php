<?php declare(strict_types=1);

namespace App\Model;

use DateTime;

class Participant
{
    private int $id = 0;
    private int $userId = 0;
    private int $eventId = 0;
    private DateTime $requestTime;
    private bool $approved = false;
    private bool $disqualified = true;

    public function __construct()
    {
        $this->requestTime = new DateTime();
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

    public function setRequestTime(DateTime $requestTime): self
    {
        $this->requestTime = $requestTime;

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
