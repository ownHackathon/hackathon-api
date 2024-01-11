<?php declare(strict_types=1);

namespace Test\Unit\App\Model;

use App\Entity\Project;
use App\Enum\DateTimeFormat;
use DateTime;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

class ProjectTest extends TestCase
{
    private Project $project;

    protected function setUp(): void
    {
        $this->project = new Project();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $projectId = $this->project->setId(1);
        $id = $projectId->getId();

        self::assertInstanceOf(Project::class, $projectId);
        self::assertIsInt($id);
        self::assertSame(1, $id);
    }

    public function testCanSetAndGetParticipantId(): void
    {
        $projectParticipantId = $this->project->setParticipantId(TestConstants::USER_ID);
        $participantId = $projectParticipantId->getParticipantId();

        self::assertInstanceOf(Project::class, $projectParticipantId);
        self::assertIsInt($participantId);
        self::assertSame(TestConstants::USER_ID, $participantId);
    }

    public function testCanSetAndGetTitle(): void
    {
        $projectTitle = $this->project->setTitle(TestConstants::EVENT_TITLE);
        $title = $projectTitle->getTitle();

        self::assertInstanceOf(Project::class, $projectTitle);
        self::assertIsString($title);
        self::assertSame(TestConstants::EVENT_TITLE, $title);
    }

    public function testCanSetAndGetDescription(): void
    {
        $projectDescription = $this->project->setDescription(TestConstants::EVENT_DESCRIPTION);
        $description = $projectDescription->getDescription();

        self::assertInstanceOf(Project::class, $projectDescription);
        self::assertIsString($description);
        self::assertSame(TestConstants::EVENT_DESCRIPTION, $description);
    }

    public function testCanSetAndGetGitRepoUri(): void
    {
        $projectGitRepoUri = $this->project->setGitRepoUri(TestConstants::PROJECT_URL);
        $gitRepoUri = $projectGitRepoUri->getGitRepoUri();

        self::assertInstanceOf(Project::class, $projectGitRepoUri);
        self::assertIsString($gitRepoUri);
        self::assertSame(TestConstants::PROJECT_URL, $gitRepoUri);
    }

    public function testCanSetAndGetDemoPageUri(): void
    {
        $projectDemoPageUri = $this->project->setDemoPageUri(TestConstants::PROJECT_URL);
        $demoPageUri = $projectDemoPageUri->getDemoPageUri();

        self::assertInstanceOf(Project::class, $projectDemoPageUri);
        self::assertIsString($demoPageUri);
        self::assertSame(TestConstants::PROJECT_URL, $demoPageUri);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $projectCreateTime = $this->project->setCreateTime(new DateTime(TestConstants::TIME));
        $createTime = $projectCreateTime->getCreateTime();

        self::assertInstanceOf(Project::class, $projectCreateTime);
        self::assertInstanceOf(DateTime::class, $createTime);
        self::assertSame(TestConstants::TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }
}
