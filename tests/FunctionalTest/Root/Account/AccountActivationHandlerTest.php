<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Root\Account;

use DateTimeImmutable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use ownHackathon\App\Entity\AccountActivation;
use ownHackathon\Core\Message\DataType;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use ownHackathon\FunctionalTest\AbstractFunctional;
use ownHackathon\UnitTest\JsonRequestHelper;

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
            email: new Email('test@example.com'),
            token: $uuid->uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $activationRepository->insert($testAccountActivate);

        $request = new ServerRequest(
            uri: '/api/account/activation/' . $testAccountActivate->token->getHex()->toString(),
            method: 'POST'
        );
        $request = $request->withParsedBody([
            'accountName' => 'Test',
            'password' => 'TestBlaBlubb',
        ]);
        $response = $this->app->handle($request);

        $emptyAccountActivate = $activationRepository->findByToken(
            $testAccountActivate->token->getHex()->toString()
        );

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
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
            'statusCode' => Assert::isType(DataType::INT),
            'message' => Assert::isType(DataType::STRING),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(ResponseMessage::TOKEN_INVALID, $content['message']);
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
            'statusCode' => Assert::isType(DataType::INT),
            'message' => Assert::isType(DataType::STRING),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(ResponseMessage::DATA_INVALID, $content['message']);
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
            'statusCode' => Assert::isType(DataType::INT),
            'message' => Assert::isType(DataType::STRING),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(ResponseMessage::TOKEN_INVALID, $content['message']);
    }
}
