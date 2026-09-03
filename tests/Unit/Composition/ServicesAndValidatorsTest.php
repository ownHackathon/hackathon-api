<?php declare(strict_types=1);

namespace Tests\Unit\Composition;

use Laminas\Diactoros\ServerRequest;
use App\Account\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use App\Account\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use App\Account\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use Core\Http\Exception\HttpInvalidArgumentException;
use Core\Http\Exception\HttpUnauthorizedException;
use App\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;
use App\Account\Identity\DTO\Client\ClientIdentificationData;
use App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use App\Account\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use App\Account\Identity\Infrastructure\Validator\DateLessNow;
use App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use App\Mailing\Infrastructure\Validator\EMailValidator;
use App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use App\Workspace\Infrastructure\Service\PaginationTotalPages;
use App\Workspace\Infrastructure\Service\PaginationService;
use App\Workspace\Infrastructure\Service\SlugService;
use App\Policy\Domain\Enum\Visibility;
use App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use Core\Persistence\Pagination;
use Core\SharedKernel\Utils\UuidFactory;
use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use Laminas\Filter\ConfigProvider as FilterConfigProvider;
use Laminas\InputFilter\ConfigProvider as InputFilterConfigProvider;
use Laminas\InputFilter\Factory;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Validator\ConfigProvider as ValidatorConfigProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Psr\Log\LoggerInterface;

use function expect;
use function password_hash;
use function test;

/**
 * Container-loser Zugriff auf die Laminas-Factory für die Validator-Bausteine.
 * Es werden ausschließlich die fachneutralen Laminas-ConfigProvider geladen, keine
 * App-Services und keine Datenbank. Dadurch bleibt der Test vom App-Container unabhängig.
 */
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
    expect((new SlugService())->getSlugFromString(' Hello__World! '))->toBe('hello-world')
        ->and((new PaginationTotalPages())->getTotalPages(0, 10))->toBe(1)
        ->and((new PaginationTotalPages())->getTotalPages(21, 10))->toBe(3);
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

test('password input applies required and length rules', function (): void {
    $factory = createLaminasFactory();
    $password = new PasswordValidator($factory);
    $password->setData(['password' => 'secret']);
    expect($password->isValid())->toBeTrue();
    $password->setData(['password' => 'x']);
    expect($password->isValid())->toBeFalse();
    $password->setData(['password' => null]);
    expect($password->isValid())->toBeFalse();
});

test('all identity input validators enforce their contracts', function (): void {
    $factory = createLaminasFactory();

    $name = new AccountActivationValidator($factory);
    $name->setData(['accountName' => 'x']);
    expect($name->isValid())->toBeFalse();

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

test('workspace input validators cover optional and bounded fields', function (): void {
    $factory = createLaminasFactory();
    $validator = new WorkspaceCreateValidator($factory);

    $validator->setData(['name' => 'Workspace', 'description' => '', 'details' => '', 'visibility' => (string) Visibility::PUBLIC->value]);
    expect($validator->isValid())->toBeTrue()
        ->and($validator->getValues()['name'])->toBe('Workspace');

    $validator->setData(['name' => 'ä', 'description' => '', 'details' => '', 'visibility' => (string) Visibility::PUBLIC->value]);
    expect($validator->isValid())->toBeFalse();

    $validator->setData(['name' => 'Workspace', 'description' => '  description  ', 'details' => null, 'visibility' => (string) Visibility::PUBLIC->value]);
    expect($validator->isValid())->toBeTrue()
        ->and($validator->getValues()['description'])->toBe('description');

    $validator->setData(['name' => 'Workspace', 'description' => '', 'details' => '', 'visibility' => (string) (Visibility::PUBLIC->value + 1)]);
    expect($validator->isValid())->toBeFalse();
});

test('composed workspace and email validators validate complete payloads', function (): void {
    $factory = createLaminasFactory();

    $workspace = new WorkspaceCreateValidator($factory);
    $workspace->setData(['name' => 'Team', 'description' => '', 'details' => '', 'visibility' => Visibility::PUBLIC->value]);
    expect($workspace->isValid())->toBeTrue();
    $workspace->setData(['name' => 'x', 'description' => str_repeat('x', 256), 'details' => '', 'visibility' => Visibility::PUBLIC->value + 1]);
    expect($workspace->isValid())->toBeFalse();

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
        ->toThrow(HttpUnauthorizedException::class)
        ->and(fn () => (new WorkspaceCreateValidatorMiddleware(new WorkspaceCreateValidator($factory)))
            ->process(malformedRequest(['name' => []]), $handler))
        ->toThrow(HttpInvalidArgumentException::class);
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
        $this->createMock(LoggerInterface::class),
    ))->process((new ServerRequest())->withHeader('Authorization', 'token'), $handler))
        ->toThrow(HttpUnauthorizedException::class);
});

test('pagination service builds metadata from repository count', function (): void {
    $repository = $this->createMock(WorkspaceRepositoryInterface::class);
    $repository->expects($this->once())->method('countByAccount')->with(7)->willReturn(21);
    $metadata = (new PaginationService($repository, new PaginationTotalPages()))
        ->getMetaDataByAccountId(new Pagination(2, 10, 10), 7);
    expect($metadata->totalItems)->toBe(21)
        ->and($metadata->totalPages)->toBe(3)
        ->and($metadata->currentPage)->toBe(2);
});