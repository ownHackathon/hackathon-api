<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Root\Account;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\ServerRequest;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Entity\Token\TokenInterface;
use ownHackathon\Core\Enum\Message\StatusMessage;
use ownHackathon\Core\Enum\DataType;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\FunctionalTest\AbstractFunctional;
use ownHackathon\UnitTest\JsonRequestHelper;
use PHPUnit\Framework\Assert;

class AccountRegisterHandlerTest extends AbstractFunctional
{
    use JsonRequestHelper;
    use InvalidEMailAddressProviderTrait;

    public function testBodyIsEmpty(): void
    {
        $request = new ServerRequest(
            uri: '/api/account',
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

    public function testBodyHasInvalidParameter(): void
    {
        $request = new ServerRequest(
            uri: '/api/account',
            method: 'POST'
        );

        $response = $this->app->handle($request);
        $request = $request->withParsedBody(['password' => 'password']);
        $content = $this->getContentAsJson($response);

        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertThat($response, $this->bodyMatchesJson([
            'statusCode' => Assert::isType(DataType::INTEGER->value),
            'message' => Assert::isType(DataType::STRING->value),
        ]));
        $this->assertSame(HTTP::STATUS_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(StatusMessage::INVALID_DATA->value, $content['message']);
    }

    public function testActivationDataSetWasCreated(): void
    {
        $request = new ServerRequest(
            uri: '/api/account',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => 'Tester@example.com']);
        $response = $this->app->handle($request);

        /** @var AccountActivationRepositoryInterface $repository */
        $repository = $this->container->get(AccountActivationRepositoryInterface::class);
        $activationDataSet = $repository->findEmail(new Email('Tester@example.com'));

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertArrayHasKey(0, $activationDataSet);
        $this->assertInstanceOf(AccountActivationInterface::class, $activationDataSet[0]);
    }

    public function testPasswordDataSetWasCreated(): void
    {
        /** @var AccountRepositoryInterface $accountRepository */
        $accountRepository = $this->container->get(AccountRepositoryInterface::class);
        $account = $accountRepository->findByEmail(new Email('user@example.com'));

        /** @var TokenRepositoryInterface $tokenRepository */
        $tokenRepository = $this->container->get(TokenRepositoryInterface::class);
        $tokenRepository->deleteByAccountId($account->id);

        $request = new ServerRequest(
            uri: '/api/account',
            method: 'POST'
        );
        $request = $request->withParsedBody(['email' => 'user@example.com']);
        $response = $this->app->handle($request);

        $activationDataSet = $tokenRepository->findByAccountId($account->id);

        $this->assertSame(HTTP::STATUS_OK, $response->getStatusCode());
        $this->assertArrayHasKey(0, $activationDataSet);
        $this->assertInstanceOf(TokenInterface::class, $activationDataSet[0]);
    }
}
