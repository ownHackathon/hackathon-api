<?php declare(strict_types=1);

namespace Tests\Unit;

use Laminas\Diactoros\ServerRequest;
use ownHackathon\App\Account\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\App\Http\Exception\HttpUnauthorizedException;
use ownHackathon\App\Workspace\Middleware\WorkspaceCreateValidatorMiddleware;
use ownHackathon\App\Account\Identity\DTO\Client\ClientIdentificationData;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\DateLessNow;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\AccountNameInput;
use ownHackathon\App\Mailing\Infrastructure\Validator\EMailValidator;
use ownHackathon\App\Mailing\Infrastructure\Validator\Input\EmailInput;
use ownHackathon\App\Workspace\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\App\Workspace\Infrastructure\Service\PaginationService;
use ownHackathon\App\Workspace\Infrastructure\Service\SlugService;
use ownHackathon\App\Http\Validator\Input\VisibilityInput;
use ownHackathon\Core\SharedKernel\Domain\Enum\Visibility;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\WorkspaceCreateValidator;
use ownHackathon\Core\Persistence\Pagination;
use ownHackathon\Core\SharedKernel\Utils\UuidFactory;
use ownHackathon\App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use Psr\Log\LoggerInterface;

use function expect;
use function password_hash;
use function test;

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
    $input = new PasswordInput();
    $input->setValue('secret');
    expect($input->isValid())->toBeTrue();
    $input->setValue('x');
    expect($input->isValid())->toBeFalse();
    $input->setValue(null);
    expect($input->isValid())->toBeFalse();
});

test('all identity input validators enforce their contracts', function (): void {
    $name = new AccountNameInput();
    $name->setValue('  Alice  ');
    expect($name->isValid())->toBeTrue()->and($name->getValue())->toBe('Alice');
    $name->setValue('x');
    expect($name->isValid())->toBeFalse();

    $activation = new AccountActivationValidator(new AccountNameInput(), new PasswordInput());
    $activation->setData(['accountName' => 'Alice', 'password' => 'secret']);
    expect($activation->isValid())->toBeTrue();
    $activation->setData(['accountName' => 'x', 'password' => 'x']);
    expect($activation->isValid())->toBeFalse();

    $authentication = new AuthenticationValidator(new EmailInput(), new PasswordInput());
    $authentication->setData(['email' => 'invalid', 'password' => 'secret']);
    expect($authentication->isValid())->toBeFalse();

    $password = new \ownHackathon\App\Account\Identity\Infrastructure\Validator\PasswordValidator(new PasswordInput());
    $password->setData(['password' => 'secret']);
    expect($password->isValid())->toBeTrue();
    $password->setData(['password' => 'x']);
    expect($password->isValid())->toBeFalse();
});

test('workspace input validators cover optional and bounded fields', function (): void {
    $name = new WorkspaceNameInput();
    $name->setValue(' Workspace ');
    expect($name->isValid())->toBeTrue()->and($name->getValue())->toBe('Workspace');
    $name->setValue('ä');
    expect($name->isValid())->toBeFalse();

    $description = new WorkspaceDescriptionInput();
    $description->setValue('  description  ');
    expect($description->isValid())->toBeTrue()->and($description->getValue())->toBe('description');

    $details = new WorkspaceDetailsInput();
    $details->setValue(null);
    expect($details->isValid())->toBeTrue();

    $visibility = new VisibilityInput();
    $visibility->setValue(' ' . Visibility::PUBLIC->value . ' ');
    expect($visibility->isValid())->toBeTrue()->and($visibility->getValue())->toBe((string) Visibility::PUBLIC->value);
    expect($visibility->getName())->toBe('visibility');
});

test('composed workspace and email validators validate complete payloads', function (): void {
    $workspace = new WorkspaceCreateValidator(
        new WorkspaceNameInput(),
        new WorkspaceDescriptionInput(),
        new WorkspaceDetailsInput(),
        new VisibilityInput(),
    );
    $workspace->setData(['name' => 'Team', 'description' => '', 'details' => '', 'visibility' => Visibility::PUBLIC->value]);
    expect($workspace->isValid())->toBeTrue();
    $workspace->setData(['name' => 'x', 'description' => str_repeat('x', 256), 'details' => '', 'visibility' => Visibility::PUBLIC->value + 1]);
    expect($workspace->isValid())->toBeFalse();

    $email = new EMailValidator(new EmailInput());
    expect($email->has('email'))->toBeTrue();
    $email->setData(['email' => 'invalid']);
    expect($email->isValid())->toBeFalse();
});

test('request validation converts malformed field types to controlled HTTP errors', function (): void {
    $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
    $handler->expects($this->never())->method('handle');

    expect(fn () => (new EmailInputValidatorMiddleware(new EMailValidator(new EmailInput())))
        ->process(malformedRequest(['email' => []]), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new PasswordInputValidatorMiddleware(new \ownHackathon\App\Account\Identity\Infrastructure\Validator\PasswordValidator(new PasswordInput())))
            ->process(malformedRequest(['password' => []]), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new ActivationInputValidatorMiddleware(new AccountActivationValidator(new AccountNameInput(), new PasswordInput())))
            ->process(malformedRequest(['accountName' => [], 'password' => 'secret']), $handler))
        ->toThrow(HttpInvalidArgumentException::class)
        ->and(fn () => (new AuthenticationValidationMiddleware(new AuthenticationValidator(new EmailInput(), new PasswordInput())))
            ->process(malformedRequest(['email' => [], 'password' => 'secret']), $handler))
        ->toThrow(HttpUnauthorizedException::class)
        ->and(fn () => (new WorkspaceCreateValidatorMiddleware(new WorkspaceCreateValidator(
            new WorkspaceNameInput(),
            new WorkspaceDescriptionInput(),
            new WorkspaceDetailsInput(),
            new VisibilityInput(),
        )))->process(malformedRequest(['name' => []]), $handler))
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
