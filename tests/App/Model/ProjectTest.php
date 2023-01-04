<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Model\Project;
use App\Test\Mock\TestConstants;
use DateTime;
use PHPUnit\Framework\TestCase;

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

        $this->assertInstanceOf(Project::class, $projectId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetParticipantId(): void
    {
        $projectParticipantId = $this->project->setParticipantId(TestConstants::USER_ID);
        $participantId = $projectParticipantId->getParticipantId();

        $this->assertInstanceOf(Project::class, $projectParticipantId);
        $this->assertIsInt($participantId);
        $this->assertSame(TestConstants::USER_ID, $participantId);
    }

    public function testCanSetAndGetTitle(): void
    {
        $projectTitle = $this->project->setTitle(TestConstants::EVENT_TITLE);
        $title = $projectTitle->getTitle();

        $this->assertInstanceOf(Project::class, $projectTitle);
        $this->assertIsString($title);
        $this->assertSame(TestConstants::EVENT_TITLE, $title);
    }

    public function testCanSetAndGetDescription(): void
    {
        $projectDescription = $this->project->setDescription(TestConstants::EVENT_DESCRIPTION);
        $description = $projectDescription->getDescription();

        $this->assertInstanceOf(Project::class, $projectDescription);
        $this->assertIsString($description);
        $this->assertSame(TestConstants::EVENT_DESCRIPTION, $description);
    }

    public function testCanSetAndGetGitRepoUri(): void
    {
        $projectGitRepoUri = $this->project->setGitRepoUri(TestConstants::PROJECT_URL);
        $gitRepoUri = $projectGitRepoUri->getGitRepoUri();

        $this->assertInstanceOf(Project::class, $projectGitRepoUri);
        $this->assertIsString($gitRepoUri);
        $this->assertSame(TestConstants::PROJECT_URL, $gitRepoUri);
    }

    public function testCanSetAndGetDemoPageUri(): void
    {
        $projectDemoPageUri = $this->project->setDemoPageUri(TestConstants::PROJECT_URL);
        $demoPageUri = $projectDemoPageUri->getDemoPageUri();

        $this->assertInstanceOf(Project::class, $projectDemoPageUri);
        $this->assertIsString($demoPageUri);
        $this->assertSame(TestConstants::PROJECT_URL, $demoPageUri);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $projectCreateTime = $this->project->setCreateTime(new DateTime(TestConstants::TIME));
        $createTime = $projectCreateTime->getCreateTime();

        $this->assertInstanceOf(Project::class, $projectCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame(TestConstants::TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }
}
