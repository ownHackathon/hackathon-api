<?php declare(strict_types=1);

namespace App\Model;

class EventRatingCategory
{
    protected int $id = 0;
    protected int $eventId = 0;
    protected int $ratingCategoryId = 0;
    protected int $weighting = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

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

    public function getRatingCategoryId(): int
    {
        return $this->ratingCategoryId;
    }

    public function setRatingCategoryId(int $ratingCategoryId): self
    {
        $this->ratingCategoryId = $ratingCategoryId;

        return $this;
    }

    public function getWeighting(): int
    {
        return $this->weighting;
    }

    public function setWeighting(int $weighting): self
    {
        $this->weighting = $weighting;

        return $this;
    }
}
