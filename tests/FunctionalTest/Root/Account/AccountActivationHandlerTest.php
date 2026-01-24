<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use Shared\Domain\Enum\DataType;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Utils\UuidFactoryInterface;
use UnitTest\JsonRequestHelper;

class AccountActivationHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;

    public function testCanActivateAccount(): void
    {
        /** @var UuidFactoryInterface $uuid */
        $uuid = $this->container->get(UuidFactoryInterface::class);

        /** @var AccountActivationRepositoryInterface $activationRepository */
        $activationRepository = $this->container->get(AccountActivationRepositoryInterface::class);

        $testAccountActivate = new AccountActivation(
            id: null,
            email: new EmailType('test@example.com'),
            token: $uuid->uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $activationRepository->insert($testAccountActivate);

        $request = new ServerRequest(
            uri: '/api/account/activation/' . $testAccountActivate->token->toString(),
            method: 'POST'
        );
        $request = $request->withParsedBody([
            'accountName' => 'Test',
            'password' => 'TestBlaBlubb',
        ]);
        $response = $this->app->handle($request);

        $emptyAccountActivate = $activationRepository->findByToken(
            $testAccountActivate->token->toString()
        );

        $this->assertSame(HTTP::STATUS_CREATED, $response->getStatusCode());
        $this->assertNull($emptyAccountActivate);
    }

    public function testTokenNotGiven(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/activation/',
            method: 'POST'
        );
        $request = $request->withParsedBody([
            'accountName' => 'Test',
            'password' => 'TestBlaBlubb',
        ]);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(StatusMessage::TOKEN_INVALID->value, $content['message']);
    }

    public function testBodyIsInvalid(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/activation/',
            method: 'POST'
        );

        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(StatusMessage::INVALID_DATA->value, $content['message']);
    }

    public function testTokenIsInvalidOrNotPersistent(): void
    {
        $request = new ServerRequest(
            uri: '/api/account/activation/1ddwrer2',
            method: 'POST'
        );
        $request = $request->withParsedBody([
            'accountName' => 'Test',
            'password' => 'TestBlaBlubb',
        ]);
        $response = $this->app->handle($request);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(StatusMessage::TOKEN_INVALID->value, $content['message']);
    }
}
