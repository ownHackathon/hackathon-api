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
use ownHackathon\Workspace\Domain\Message\WorkspaceStatusMessage;
use PHPUnit\Framework\Assert;
use UnitTest\JsonRequestHelper;

class ListOwnWorkspacesHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    public string $accessToken;
    public UuidFactoryInterface $uuid;

    public function setUp(): void
    {
        parent::setUp();

        $this->uuid = $this->container->get(UuidFactoryInterface::class);

        $accountRepository = $this->container->get(AccountRepositoryInterface::class);

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

    public function testAuthorizationMissed(): void
    {
        $request = new ServerRequest(
            uri: '/api/me/workspaces',
            method: 'GET',
        );

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

    public function testStatusOk(): void
    {
        $request = new ServerRequest(
            uri: '/api/me/workspaces',
            method: 'GET',
            headers: ['Authorization' => $this->accessToken],
        );

        $response = $this->app->handle($request);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
    }
}
