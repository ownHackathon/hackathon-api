<?php
declare(strict_types=1);

namespace App\Model;

use DateTime;

class Project
{
    protected int $id;
    protected int $participantId;
    protected string $title;
    protected string $description;
    protected string $gitRepoUri;
    protected string $demoPageUri;
    protected DateTime $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getParticipantId(): int
    {
        return $this->participantId;
    }

    public function setParticipantId(int $participantId): self
    {
        $this->participantId = $participantId;

        return $this;
    }

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

    public function getGitRepoUri(): string
    {
        return $this->gitRepoUri;
    }

    public function setGitRepoUri(string $gitRepoUri): self
    {
        $this->gitRepoUri = $gitRepoUri;

        return $this;
    }

    public function getDemoPageUri(): string
    {
        return $this->demoPageUri;
    }

    public function setDemoPageUri(string $demoPageUri): self
    {
        $this->demoPageUri = $demoPageUri;

        return $this;
    }

    public function getCreateTime(): DateTime
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): self
    {
        $this->createTime = new DateTime($createTime);

        return $this;
    }
}
