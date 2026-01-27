<?php declare(strict_types=1);

namespace FunctionalTest\Root\Workspace;

use DateTimeImmutable;
use Exdrals\Identity\Domain\Account;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Domain\Enum\DataType;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Workspace\Domain\Message\WorkspaceStatusMessage;
use ownHackathon\Workspace\Domain\Workspace;
use PHPUnit\Framework\Assert;
use UnitTest\JsonRequestHelper;

class WorkspaceCreateHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    public string $accessToken;
    public UuidFactoryInterface $uuid;

    public function setUp(): void
    {
        parent::setUp();

        /** @var UuidFactoryInterface this->uuid */
        $this->uuid = $this->container->get(UuidFactoryInterface::class);

        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->container->get(AccountRepositoryInterface::class);
        /** @var UuidFactoryInterface $uuid */

        $account = new Account(
            null,
            $this->uuid->uuid7(),
            'I see your Token',
            password_hash('I see your Token', PASSWORD_DEFAULT),
            new EmailType('iseeyourtoken@example.com'),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );

        $accountRepository->insert($account);

        $request = new ServerRequest(
            uri: '/api/account/authentication',
            method: 'POST'
        );
        $request = $request->withParsedBody(
            ['email' => $account->email->toString(), 'password' => 'I see your Token']
        );

        $response = $this->app->handle($request);

        $content = $this->getContentAsJson($response);

        $this->accessToken = $content['accessToken'];
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->resetDatabase();
    }

    public function testBodyIsEmpty(): void
    {
        $request = new ServerRequest(
            uri: '/api/workspace',
            method: 'POST',
            headers: ['Authorization' => $this->accessToken],
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(WorkspaceStatusMessage::INVALID_WORKSPACE_NAME, $content['message']);
    }

    public function testBodyHasInvalidParameter(): void
    {
        $request = new ServerRequest(
            uri: '/api/workspace',
            method: 'POST',
            headers: ['Authorization' => $this->accessToken],
        );
        $request = $request->withParsedBody(['workspaceName' => 'Das ist Türlicht falsch']);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(WorkspaceStatusMessage::INVALID_WORKSPACE_NAME, $content['message']);
    }

    public function testAuthorizationMissed(): void
    {
        $request = new ServerRequest(
            uri: '/api/workspace',
            method: 'POST',
        );
        $request = $request->withParsedBody(['workspaceName' => 'Das ist Türlicht falsch']);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_UNAUTHORIZED, $response->getStatusCode());
        $this->assertSame(WorkspaceStatusMessage::UNAUTHORIZED_ACCESS, $content['message']);
    }

    public function testWorkspaceAlreadyInUse(): void
    {
        $workspaceRepository = $this->container->get(WorkspaceRepositoryInterface::class);

        $workspace = new Workspace(
            id: null,
            uuid: $this->uuid->uuid7(),
            accountId: 1,
            name: 'Duplicated Entry',
            slug: 'duplicated-entry',
        );

        $workspaceRepository->insert($workspace);

        $request = new ServerRequest(
            uri: '/api/workspace',
            method: 'POST',
            headers: ['Authorization' => $this->accessToken],
        );
        $request = $request->withParsedBody(['workspaceName' => 'Duplicated Entry']);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_CONFLICT, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_CONFLICT, $response->getStatusCode());
        $this->assertSame(WorkspaceStatusMessage::DUPLICATED_WORKSPACE_NAME, $content['message']);
    }

    public function testWorkspaceWasCreated(): void
    {
        $request = new ServerRequest(
            uri: '/api/workspace',
            method: 'POST',
            headers: ['Authorization' => $this->accessToken],
        );
        $request = $request->withParsedBody(['workspaceName' => 'Created Workspace']);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_CREATED, $response->getStatusCode());
        $this->assertSame('/api/workspace/created-workspace', $response->getHeaderLine('Location'));
        $this->assertSame('Created Workspace', $content['name']);
        $this->assertSame('created-workspace', $content['slug']);
    }
}
