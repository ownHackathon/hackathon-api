<?php declare(strict_types=1);

namespace App\Account\Identity;

use Envms\FluentPDO\Query;
use Laminas\InputFilter\Factory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;
use App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use App\Account\Identity\Handler\AccessTokenHandler;
use App\Account\Identity\Handler\AccountActivationHandler;
use App\Account\Identity\Handler\AccountPasswordForgottenHandler;
use App\Account\Identity\Handler\AccountPasswordHandler;
use App\Account\Identity\Handler\AccountRegisterHandler;
use App\Account\Identity\Handler\AuthenticationHandler;
use App\Account\Identity\Handler\LogoutHandler;
use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Application\Port\IdentityLoggerInterface;
use App\Account\Identity\Infrastructure\Factory\ActivityLoggerFactory;
use App\Account\Identity\Infrastructure\Factory\EmailHashSaltProviderFactory;
use App\Account\Identity\Infrastructure\Factory\IdentityLoggerFactory;
use App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator;
use App\Account\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydrator;
use App\Account\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use App\Account\Identity\Infrastructure\Hydrator\AccountHydrator;
use App\Account\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use App\Account\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository;
use App\Account\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository;
use App\Account\Identity\Infrastructure\Persistence\Repository\AccountRepository;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountActivationTable;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use App\Account\Identity\Infrastructure\Persistence\Table\AccountTable;
use App\Account\Identity\Infrastructure\Service\Account\AccountAuthenticationService;
use App\Account\Identity\Infrastructure\Service\Account\AccountCreatorService;
use App\Account\Identity\Infrastructure\Service\Account\AccountRegisterService;
use App\Account\Identity\Infrastructure\Service\Account\AccountService;
use App\Account\Identity\Infrastructure\Service\Account\PasswordChangeService;
use App\Account\Identity\Infrastructure\Service\Account\PasswordService;
use App\Account\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use App\Account\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenService;
use App\Account\Identity\Infrastructure\Service\Token\AccessTokenServiceFactory;
use App\Account\Identity\Infrastructure\Service\Token\ActivationTokenService;
use App\Account\Identity\Infrastructure\Service\Token\ActivationTokenServiceFactory;
use App\Account\Identity\Infrastructure\Service\Token\PasswordTokenService;
use App\Account\Identity\Infrastructure\Service\Token\PasswordTokenServiceFactory;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenService;
use App\Account\Identity\Infrastructure\Service\Token\RefreshTokenServiceFactory;
use App\Account\Identity\Infrastructure\Validator\AccountActivationValidator;
use App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use App\Account\Identity\Middleware\Account\AccountActivityLoggingMiddleware;
use App\Account\Identity\Middleware\Account\Authentication\AuthenticationConditionsMiddleware;
use App\Account\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use App\Account\Identity\Middleware\Account\LastActivityUpdaterMiddleware;
use App\Account\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use App\Account\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use App\Account\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use App\Account\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use App\Account\Identity\Middleware\IdentityExceptionMappingMiddleware;
use App\Account\Identity\Middleware\RequireLoginMiddleware;
use App\Account\Identity\Middleware\Token\AccessTokenValidationMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenAccountMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenValidationMiddleware;
use App\Account\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use App\Mailing\Infrastructure\Validator\EMailValidator;
use Core\Persistence\Middleware\FluentTransactionMiddleware;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use App\Token\Domain\Repository\TokenRepositoryInterface;

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
                    RefreshTokenDatabaseExistenceMiddleware::class,
                    RefreshTokenMatchClientIdentificationMiddleware::class,
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
                IdentityLoggerInterface::class => IdentityLoggerFactory::class,
                ActivityLoggerInterface::class => ActivityLoggerFactory::class,
                EmailHashSaltProviderInterface::class => EmailHashSaltProviderFactory::class,
                AccountAccessAuthHydrator::class => InvokableFactory::class,
                AccountActivationHydrator::class => ConfigAbstractFactory::class,
                AccountHydrator::class => ConfigAbstractFactory::class,
                AuthenticationConditionsMiddleware::class => InvokableFactory::class,
                AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                IdentityExceptionMappingMiddleware::class => ConfigAbstractFactory::class,
                PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                LastActivityUpdaterMiddleware::class => ConfigAbstractFactory::class,
                AccountActivityLoggingMiddleware::class => ConfigAbstractFactory::class,
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
                IdentityLoggerInterface::class,
            ],
            AccountHydrator::class => [
                UuidFactoryInterface::class,
                IdentityLoggerInterface::class,
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
            IdentityExceptionMappingMiddleware::class => [
                EmailHashSaltProviderInterface::class,
            ],
            PasswordInputValidatorMiddleware::class => [
                PasswordValidator::class,
            ],
            LastActivityUpdaterMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            AccountActivityLoggingMiddleware::class => [
                ActivityLoggerInterface::class,
            ],
            PasswordChangeService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
                ActivityLoggerInterface::class,
            ],
            RequestAuthenticationMiddleware::class => [
                AccessTokenService::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                IdentityLoggerInterface::class,
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
                ActivityLoggerInterface::class,
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
                Factory::class,
            ],
            AuthenticationValidator::class => [
                Factory::class,
            ],
            EMailValidator::class => [
                Factory::class,
            ],
            PasswordValidator::class => [
                Factory::class,
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
                ActivityLoggerInterface::class,
                EmailHashSaltProviderInterface::class,
            ],
            AccountRegisterHandler::class => [
                AccountRegisterService::class,
            ],
            AccountRegisterService::class => [
                AccountService::class,
                AccountActivationRepositoryInterface::class,
                ActivationTokenService::class,
                UuidFactoryInterface::class,
                ActivityLoggerInterface::class,
                EmailHashSaltProviderInterface::class,
            ],
            AccountActivationHandler::class => [
                AccountCreatorService::class,
                UrlHelper::class,
            ],
            AccountCreatorService::class => [
                AccountActivationRepositoryInterface::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                ActivityLoggerInterface::class,
                EmailHashSaltProviderInterface::class,
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
