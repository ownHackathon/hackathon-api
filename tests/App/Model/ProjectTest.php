<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Model\Project;
use DateTime;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private const TEST_USER_ID = 1;
    private const TEST_TITLE = 'Test Title';
    private const TEST_DESCRIPTION = 'Test Description';
    private const TEST_URL = 'https://github.com';
    private const TEST_TIME = '1000-01-01 00:00';

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

        $this->assertInstanceOf(Project::class, $projectId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetParticipantId(): void
    {
        $projectParticipantId = $this->project->setParticipantId(self::TEST_USER_ID);
        $participantId = $projectParticipantId->getParticipantId();

        $this->assertInstanceOf(Project::class, $projectParticipantId);
        $this->assertIsInt($participantId);
        $this->assertSame(self::TEST_USER_ID, $participantId);
    }

    public function testCanSetAndGetTitle(): void
    {
        $projectTitle = $this->project->setTitle(self::TEST_TITLE);
        $title = $projectTitle->getTitle();

        $this->assertInstanceOf(Project::class, $projectTitle);
        $this->assertIsString($title);
        $this->assertSame(self::TEST_TITLE, $title);
    }

    public function testCanSetAndGetDescription(): void
    {
        $projectDescription = $this->project->setDescription(self::TEST_DESCRIPTION);
        $description = $projectDescription->getDescription();

        $this->assertInstanceOf(Project::class, $projectDescription);
        $this->assertIsString($description);
        $this->assertSame(self::TEST_DESCRIPTION, $description);
    }

    public function testCanSetAndGetGitRepoUri(): void
    {
        $projectGitRepoUri = $this->project->setGitRepoUri(self::TEST_URL);
        $gitRepoUri = $projectGitRepoUri->getGitRepoUri();

        $this->assertInstanceOf(Project::class, $projectGitRepoUri);
        $this->assertIsString($gitRepoUri);
        $this->assertSame(self::TEST_URL, $gitRepoUri);
    }

    public function testCanSetAndGetDemoPageUri(): void
    {
        $projectDemoPageUri = $this->project->setDemoPageUri(self::TEST_URL);
        $demoPageUri = $projectDemoPageUri->getDemoPageUri();

        $this->assertInstanceOf(Project::class, $projectDemoPageUri);
        $this->assertIsString($demoPageUri);
        $this->assertSame(self::TEST_URL, $demoPageUri);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $projectCreateTime = $this->project->setCreateTime(new DateTime(self::TEST_TIME));
        $createTime = $projectCreateTime->getCreateTime();

        $this->assertInstanceOf(Project::class, $projectCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame(self::TEST_TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }
}
