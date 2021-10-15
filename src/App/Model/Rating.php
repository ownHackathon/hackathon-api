<?php declare(strict_types=1);

namespace App\Model;

class Rating
{
    protected int $id;
    protected int $userId;
    protected int $projectId;
    protected int $eventRatingId;
    protected int $eventRatingCategorie;
    protected int $rating;

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

    public function getEventRatingCategorie(): int
    {
        return $this->eventRatingCategorie;
    }

    public function setEventRatingCategorie(int $eventRatingCategorie): self
    {
        $this->eventRatingCategorie = $eventRatingCategorie;

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
