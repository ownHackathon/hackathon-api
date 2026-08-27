<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\Handler\AccessTokenHandler;
use ownHackathon\App\Account\Identity\Handler\AccountActivationHandler;
use ownHackathon\App\Account\Identity\Handler\AccountPasswordForgottenHandler;
use ownHackathon\App\Account\Identity\Handler\AccountPasswordHandler;
use ownHackathon\App\Account\Identity\Handler\AccountRegisterHandler;
use ownHackathon\App\Account\Identity\Handler\AuthenticationHandler;
use ownHackathon\App\Account\Identity\Handler\LogoutHandler;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydrator;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use ownHackathon\App\Account\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Repository\AccountRepository;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationTable;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Persistence\Table\AccountTable;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\AccountAuthenticationService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\AccountCreatorService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\AccountRegisterService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\AccountService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\PasswordChangeService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Account\PasswordService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\AccessTokenServiceFactory;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\ActivationTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\ActivationTokenServiceFactory;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\PasswordTokenServiceFactory;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\RefreshTokenServiceFactory;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\AccountNameInput;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use ownHackathon\App\Account\Identity\Middleware\Account\Authentication\AuthenticationConditionsMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\LastActivityUpdaterMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use ownHackathon\App\Account\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\IdentityExceptionMappingMiddleware;
use ownHackathon\App\Account\Identity\Middleware\RequireLoginMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\AccessTokenValidationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenAccountMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenValidationMiddleware;
use ownHackathon\App\Account\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use ownHackathon\App\Mailing\Infrastructure\Validator\EMailValidator;
use ownHackathon\App\Mailing\Infrastructure\Validator\Input\EmailInput;
use ownHackathon\Core\Persistence\Middleware\FluentTransactionMiddleware;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use ownHackathon\App\Token\Domain\Repository\TokenRepositoryInterface;
use Psr\Log\LoggerInterface;

readonly class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'routes' => $this->getRoutes(),
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getRoutes(): array
    {
        return [
            [
                'path' => '/api/account[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    EmailInputValidatorMiddleware::class,
                    AccountRegisterHandler::class,
                ],
                'name' => 'api_identity_register',
            ],
            [
                'path' => '/api/account/activation/[{token}[/]]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    ActivationInputValidatorMiddleware::class,
                    FluentTransactionMiddleware::class,
                    AccountActivationHandler::class,
                ],
                'name' => 'api_identity_activation',
            ],

            [
                'path' => '/api/account/authentication[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    AuthenticationConditionsMiddleware::class,
                    AuthenticationValidationMiddleware::class,
                    AuthenticationHandler::class,
                ],
                'name' => 'api_identity_authentication',
            ],
            [
                'path' => '/api/token/refresh[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    RefreshTokenValidationMiddleware::class,
                    AccessTokenHandler::class,
                ],
                'name' => 'api_identity_token_refresh',
            ],

            [
                'path' => '/api/account/password/forgotten[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    EmailInputValidatorMiddleware::class,
                    AccountPasswordForgottenHandler::class,
                ],
                'name' => 'api_identity_password_forgotten',
            ],
            [
                'path' => '/api/account/password/[{token}[/]]',
                'allowed_methods' => ['PATCH'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    PasswordInputValidatorMiddleware::class,
                    AccountPasswordHandler::class,
                ],
                'name' => 'api_identity_password_change',
            ],

            [
                'path' => '/api/account/logout[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    RequireLoginMiddleware::class,
                    AccessTokenValidationMiddleware::class,
                    RefreshTokenViaBodyValidationMiddleware::class,
                    LogoutHandler::class,
                ],
                'name' => 'api_identity_logout',
            ],
            [
                'path' => '/api/account/[{accountUuid:[0-9a-fA-F\-]+}[/]]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    IdentityExceptionMappingMiddleware::class,
                    RequireLoginMiddleware::class,
                ],
                'name' => 'api_account_detail',
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                AccountAccessAuthHydratorInterface::class => AccountAccessAuthHydrator::class,
                AccountActivationHydratorInterface::class => AccountActivationHydrator::class,
                AccountHydratorInterface::class => AccountHydrator::class,

                AccountRepositoryInterface::class => AccountRepository::class,
                AccountActivationRepositoryInterface::class => AccountActivationRepository::class,
                AccountAccessAuthRepositoryInterface::class => AccountAccessAuthRepository::class,
                AccountStoreInterface::class => AccountTable::class,
                AccountAccessAuthStoreInterface::class => AccountAccessAuthTable::class,
                AccountActivationStoreInterface::class => AccountActivationTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                AccountAccessAuthHydrator::class => InvokableFactory::class,
                AccountActivationHydrator::class => ConfigAbstractFactory::class,
                AccountHydrator::class => ConfigAbstractFactory::class,
                AuthenticationConditionsMiddleware::class => InvokableFactory::class,
                AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                LastActivityUpdaterMiddleware::class => ConfigAbstractFactory::class,
                PasswordChangeService::class => ConfigAbstractFactory::class,
                RequestAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                ClientIdentificationMiddleware::class => ConfigAbstractFactory::class,
                AccessTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                RefreshTokenAccountMiddleware::class => ConfigAbstractFactory::class,
                RefreshTokenDatabaseExistenceMiddleware::class => ConfigAbstractFactory::class,
                RefreshTokenMatchClientIdentificationMiddleware::class => InvokableFactory::class,
                RefreshTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                RefreshTokenViaBodyValidationMiddleware::class => ConfigAbstractFactory::class,
                AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                AccountActivationRepository::class => ConfigAbstractFactory::class,
                AccountRepository::class => ConfigAbstractFactory::class,
                AccountService::class => ConfigAbstractFactory::class,
                AuthenticationService::class => InvokableFactory::class,
                ClientIdentificationService::class => InvokableFactory::class,
                AccessTokenService::class => AccessTokenServiceFactory::class,
                ActivationTokenService::class => ActivationTokenServiceFactory::class,
                PasswordTokenService::class => PasswordTokenServiceFactory::class,
                RefreshTokenService::class => RefreshTokenServiceFactory::class,
                AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                AccountActivationTable::class => ConfigAbstractFactory::class,
                AccountTable::class => ConfigAbstractFactory::class,

                EmailInput::class => InvokableFactory::class,
                PasswordInput::class => InvokableFactory::class,
                AccountNameInput::class => InvokableFactory::class,
                AccountActivationValidator::class => ConfigAbstractFactory::class,
                AuthenticationValidator::class => ConfigAbstractFactory::class,
                EMailValidator::class => ConfigAbstractFactory::class,
                PasswordValidator::class => ConfigAbstractFactory::class,
                AuthenticationHandler::class => ConfigAbstractFactory::class,
                AccessTokenHandler::class => ConfigAbstractFactory::class,
                AccountAuthenticationService::class => ConfigAbstractFactory::class,
                AccountRegisterHandler::class => ConfigAbstractFactory::class,
                AccountRegisterService::class => ConfigAbstractFactory::class,
                AccountActivationHandler::class => ConfigAbstractFactory::class,
                AccountCreatorService::class => ConfigAbstractFactory::class,
                AccountPasswordForgottenHandler::class => ConfigAbstractFactory::class,
                PasswordService::class => ConfigAbstractFactory::class,
                AccountPasswordHandler::class => ConfigAbstractFactory::class,
                LogoutHandler::class => ConfigAbstractFactory::class,
            ],

        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            AccountActivationHydrator::class => [
                UuidFactoryInterface::class,
            ],
            AccountHydrator::class => [
                UuidFactoryInterface::class,
            ],
            AuthenticationValidationMiddleware::class => [
                AuthenticationValidator::class,
            ],
            ActivationInputValidatorMiddleware::class => [
                AccountActivationValidator::class,
            ],
            EmailInputValidatorMiddleware::class => [
                EMailValidator::class,
            ],
            PasswordInputValidatorMiddleware::class => [
                PasswordValidator::class,
            ],
            LastActivityUpdaterMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            PasswordChangeService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
            ],
            RequestAuthenticationMiddleware::class => [
                AccessTokenService::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                LoggerInterface::class,
            ],
            ClientIdentificationMiddleware::class => [
                ClientIdentificationService::class,
            ],
            AccessTokenValidationMiddleware::class => [
                AccessTokenService::class,
            ],
            RefreshTokenAccountMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            RefreshTokenDatabaseExistenceMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
            ],
            RefreshTokenValidationMiddleware::class => [
                RefreshTokenService::class,
            ],
            RefreshTokenViaBodyValidationMiddleware::class => [
                RefreshTokenService::class,
            ],
            AccountAccessAuthRepository::class => [
                AccountAccessAuthStoreInterface::class,
                AccountAccessAuthHydratorInterface::class,
            ],
            AccountActivationRepository::class => [
                AccountActivationStoreInterface::class,
                AccountActivationHydratorInterface::class,
            ],
            AccountRepository::class => [
                AccountStoreInterface::class,
                AccountHydratorInterface::class,
            ],
            AccountService::class => [
                AccountRepositoryInterface::class,
                AccountAccessAuthRepositoryInterface::class,
                TokenRepositoryInterface::class,
                PasswordTokenService::class,
                UuidFactoryInterface::class,
            ],
            AccountAccessAuthTable::class => [
                Query::class,
            ],
            AccountActivationTable::class => [
                Query::class,
            ],
            AccountTable::class => [
                Query::class,
            ],
            AccountActivationValidator::class => [
                AccountNameInput::class,
                PasswordInput::class,
            ],
            AuthenticationValidator::class => [
                EmailInput::class,
                PasswordInput::class,
            ],
            EMailValidator::class => [
                EmailInput::class,
            ],
            PasswordValidator::class => [
                PasswordInput::class,
            ],
            AuthenticationHandler::class => [
                AccountAuthenticationService::class,
            ],
            AccessTokenHandler::class => [
                RefreshTokenService::class,
            ],
            AccountAuthenticationService::class => [
                AccountRepositoryInterface::class,
                AccountAccessAuthRepositoryInterface::class,
                AuthenticationService::class,
                RefreshTokenService::class,
                AccessTokenService::class,
                AccountService::class,
            ],
            AccountRegisterHandler::class => [
                AccountRegisterService::class,
            ],
            AccountRegisterService::class => [
                AccountService::class,
                AccountActivationRepositoryInterface::class,
                ActivationTokenService::class,
                UuidFactoryInterface::class,
            ],
            AccountActivationHandler::class => [
                AccountCreatorService::class,
                UrlHelper::class,
            ],
            AccountCreatorService::class => [
                AccountActivationRepositoryInterface::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
            ],
            AccountPasswordForgottenHandler::class => [
                PasswordService::class,
            ],
            PasswordService::class => [
                AccountService::class,
            ],
            AccountPasswordHandler::class => [
                PasswordChangeService::class,
            ],
            LogoutHandler::class => [
                AccountService::class,
            ],
        ];
    }
}
