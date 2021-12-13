<?php declare(strict_types=1);

namespace App\Model;

class ProjectCategoryRating
{
    protected string $title = '';
    protected string $description = '';
    protected int $ratingResult = 0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRatingResult(): int
    {
        return $this->ratingResult;
    }

    public function setRatingResult(int $ratingResult): self
    {
        $this->ratingResult = $ratingResult;

        return $this;
    }
}
