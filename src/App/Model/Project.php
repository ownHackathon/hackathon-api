<?php declare(strict_types=1);

namespace App\Model;

use DateTime;

class Project
{
    private int $id = 0;
    private int $participantId = 0;
    private string $title = '';
    private ?string $description = '';
    private ?string $gitRepoUri = '';
    private ?string $demoPageUri = '';
    private DateTime $createTime;

    public function __construct()
    {
        $this->createTime = new DateTime();
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

    public function setCreateTime(DateTime $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }
}
