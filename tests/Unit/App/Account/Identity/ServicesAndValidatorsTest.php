<?php declare(strict_types=1);

namespace Tests\Unit\App\Account\Identity;

use App\Account\Identity\Api\DTO\Client\ClientIdentificationData;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Infrastructure\Logger\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use App\Account\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use App\Account\Identity\Infrastructure\Validator\DateLessNow;
use App\Account\Identity\Infrastructure\Validator\EMailValidator;
use App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use App\Account\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use App\Account\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use App\Account\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use Core\Http\Exception\HttpInvalidArgumentException;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\SharedKernel\Utils\UuidFactory;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\Diactoros\ServerRequest;
use Laminas\Filter\ConfigProvider as FilterConfigProvider;
use Laminas\InputFilter\ConfigProvider as InputFilterConfigProvider;
use Laminas\InputFilter\Factory;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Validator\ConfigProvider as ValidatorConfigProvider;

use function expect;
use function password_hash;
use function test;

function createLaminasFactory(): Factory
{
    $config = (new ConfigAggregator([
        new FilterConfigProvider(),
        new ValidatorConfigProvider(),
        new InputFilterConfigProvider(),
    ]))->getMergedConfig();

    $container = new ServiceManager($config['dependencies']);

    return Factory::new($container);
}

function malformedRequest(array $data): ServerRequest
{
    return (new ServerRequest())->withParsedBody($data);
}

test('stateless services handle normal and boundary values', function (): void {
    $auth = new AuthenticationService();
    $hash = password_hash('secret', PASSWORD_DEFAULT);
    expect($auth->isPasswordMatch('secret', $hash))->toBeTrue()->and($auth->isPasswordMatch('wrong', $hash))->toBeFalse();
    $data = ClientIdentificationData::create('client', 'agent');
    $service = new ClientIdentificationService();
    expect($service->getClientIdentificationHash($data))->toBe($service->getClientIdentificationHash($data));
});

test('date validator accepts only future dates', function (): void {
    $validator = new DateLessNow();
    expect($validator->isValid('+1 day'))->toBeTrue()->and($validator->isValid('-1 day'))->toBeFalse()->and($validator->isValid('invalid date'))->toBeFalse();
});

test('all identity input validators enforce their contracts', function (): void {
    $factory = createLaminasFactory();

    $activation = new AccountActivationValidator($factory);
    $activation->setData(['accountName' => 'Alice', 'password' => 'secret']);
    expect($activation->isValid())->toBeTrue();
    $activation->setData(['accountName' => 'x', 'password' => 'x']);
    expect($activation->isValid())->toBeFalse();

    $authentication = new AuthenticationValidator($factory);
    $authentication->setData(['email' => 'invalid', 'password' => 'secret']);
    expect($authentication->isValid())->toBeFalse();
    $authentication->setData(['email' => 'alice@example.com', 'password' => 'secret']);
    expect($authentication->isValid())->toBeTrue();

    $password = new PasswordValidator($factory);
    $password->setData(['password' => 'secret']);
    expect($password->isValid())->toBeTrue();
    $password->setData(['password' => 'x']);
    expect($password->isValid())->toBeFalse();
});

test('mail validator validates complete email payloads', function (): void {
    $factory = createLaminasFactory();

    $email = new EMailValidator($factory);
    expect($email->has('email'))->toBeTrue();
    $email->setData(['email' => 'invalid']);
    expect($email->isValid())->toBeFalse();
    $email->setData(['email' => 'alice@example.com']);
    expect($email->isValid())->toBeTrue();
});

test('request validation converts malformed field types to controlled HTTP errors', function (): void {
    $factory = createLaminasFactory();
    $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
    $handler->expects($this->never())->method('handle');

    expect(fn () => (new EmailInputValidatorMiddleware(new EMailValidator($factory)))
        ->process(malformedRequest(['email' => []]), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new PasswordInputValidatorMiddleware(new PasswordValidator($factory)))
            ->process(malformedRequest(['password' => []]), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new ActivationInputValidatorMiddleware(new AccountActivationValidator($factory)))
            ->process(malformedRequest(['accountName' => [], 'password' => 'secret']), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new AuthenticationValidationMiddleware(new AuthenticationValidator($factory)))
            ->process(malformedRequest(['email' => [], 'password' => 'secret']), $handler))
        ->toThrow(HttpUnauthorizedException::class);
});

test('refresh token validation rejects non-string body values', function (): void {
    $service = $this->createMock(RefreshTokenService::class);
    $service->expects($this->never())->method('isValid');
    $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);

    expect(fn () => (new RefreshTokenViaBodyValidationMiddleware($service))
        ->process(malformedRequest(['refreshToken' => []]), $handler))
        ->toThrow(HttpUnauthorizedException::class);
});

test('authentication rejects a validly signed token with an invalid UUID claim', function (): void {
    $tokenService = $this->createMock(AccessTokenService::class);
    $tokenService->method('isValid')->willReturn(true);
    $tokenService->method('decode')->willReturn((object) ['uuid' => 'not-a-uuid']);
    $accountRepository = $this->createMock(AccountRepositoryInterface::class);
    $accountRepository->expects($this->never())->method('findOneByUuid');
    $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);

    expect(fn () => (new RequestAuthenticationMiddleware(
        $tokenService,
        $accountRepository,
        new UuidFactory(),
        $this->createMock(IdentityLoggerInterface::class),
    ))->process((new ServerRequest())->withHeader('Authorization', 'token'), $handler))
        ->toThrow(HttpUnauthorizedException::class);
});
