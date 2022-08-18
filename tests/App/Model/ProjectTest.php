<?php declare(strict_types=1);

namespace AppTest\Model;

use App\Model\Project;
use DateTime;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private Project $project;
    private string $testTime = '1000-01-01 00:00:00';

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
        $projectParticipantId = $this->project->setParticipantId(1);
        $participantId = $projectParticipantId->getParticipantId();

        $this->assertInstanceOf(Project::class, $projectParticipantId);
        $this->assertIsInt($participantId);
        $this->assertSame(1, $participantId);
    }

    public function testCanSetAndGetTitle(): void
    {
        $projectTitle = $this->project->setTitle('test');
        $title = $projectTitle->getTitle();

        $this->assertInstanceOf(Project::class, $projectTitle);
        $this->assertIsString($title);
        $this->assertSame('test', $title);
    }

    public function testCanSetAndGetDescription(): void
    {
        $projectDescription = $this->project->setDescription('test');
        $description = $projectDescription->getDescription();

        $this->assertInstanceOf(Project::class, $projectDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }

    public function testCanSetAndGetGitRepoUri(): void
    {
        $projectGitRepoUri = $this->project->setGitRepoUri('https://github.com');
        $gitRepoUri = $projectGitRepoUri->getGitRepoUri();

        $this->assertInstanceOf(Project::class, $projectGitRepoUri);
        $this->assertIsString($gitRepoUri);
        $this->assertSame('https://github.com', $gitRepoUri);
    }

    public function testCanSetAndGetDemoPageUri(): void
    {
        $projectDemoPageUri = $this->project->setDemoPageUri('https://github.com');
        $demoPageUri = $projectDemoPageUri->getDemoPageUri();

        $this->assertInstanceOf(Project::class, $projectDemoPageUri);
        $this->assertIsString($demoPageUri);
        $this->assertSame('https://github.com', $demoPageUri);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $projectCreateTime = $this->project->setCreateTime(new DateTime($this->testTime));
        $createTime = $projectCreateTime->getCreateTime();

        $this->assertInstanceOf(Project::class, $projectCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame($this->testTime, $createTime->format('Y-m-d H:i:s'));
    }
}
