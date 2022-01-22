<?php declare(strict_types=1);

namespace App\Model;

class EventRating
{
    private int $id = 0;
    private int $minPoints = 0;
    private int $maxPoints = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getMinPoints(): int
    {
        return $this->minPoints;
    }

    public function setMinPoints(int $minPoints): self
    {
        $this->minPoints = $minPoints;

        return $this;
    }

    public function getMaxPoints(): int
    {
        return $this->maxPoints;
    }

    public function setMaxPoints(int $maxPoints): self
    {
        $this->maxPoints = $maxPoints;

        return $this;
    }
}
