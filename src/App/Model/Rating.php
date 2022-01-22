<?php declare(strict_types=1);

namespace App\Model;

class Rating
{
    private int $id = 0;
    private int $userId = 0;
    private int $projectId = 0;
    private int $eventRatingId = 0;
    private int $eventRatingCategory = 0;
    private int $rating = 0;

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

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getEventRatingId(): int
    {
        return $this->eventRatingId;
    }

    public function setEventRatingId(int $eventRatingId): self
    {
        $this->eventRatingId = $eventRatingId;

        return $this;
    }

    public function getEventRatingCategory(): int
    {
        return $this->eventRatingCategory;
    }

    public function setEventRatingCategory(int $eventRatingCategory): self
    {
        $this->eventRatingCategory = $eventRatingCategory;

        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
