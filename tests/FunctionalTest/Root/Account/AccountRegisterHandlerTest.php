<?php declare(strict_types=1);

namespace FunctionalTest\Root\Account;

use Exdrals\Account\Identity\Domain\AccountActivationInterface;
use Exdrals\Account\Identity\Domain\Email;
use Exdrals\Account\Identity\Domain\TokenInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use FunctionalTest\AbstractFunctional;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\Assert;
use Shared\Domain\Enum\DataType;
use Shared\Domain\Enum\Message\StatusMessage;
use UnitTest\JsonRequestHelper;

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
