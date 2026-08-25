<?php declare(strict_types=1);

namespace Exdrals\Identity;

use Envms\FluentPDO\Query;
use Exdrals\Core\Mailing\Infrastructure\EmailService;
use Exdrals\Core\Mailing\Infrastructure\EmailServiceFactory;
use Exdrals\Core\Mailing\Infrastructure\Validator\EMailValidator;
use Exdrals\Core\Mailing\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Core\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Exdrals\Core\Shared\Middleware\FluentTransactionMiddleware;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Exdrals\Identity\Handler\AccessTokenHandler;
use Exdrals\Identity\Handler\AccountActivationHandler;
use Exdrals\Identity\Handler\AccountPasswordForgottenHandler;
use Exdrals\Identity\Handler\AccountPasswordHandler;
use Exdrals\Identity\Handler\AccountRegisterHandler;
use Exdrals\Identity\Handler\AuthenticationHandler;
use Exdrals\Identity\Handler\LogoutHandler;
use Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator;
use Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydrator;
use Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\AccountHydrator;
use Exdrals\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepository;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationTable;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountTable;
use Exdrals\Identity\Infrastructure\Service\Account\AccountAuthenticationService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountCreatorService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountRegisterService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordChangeService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordService;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenServiceFactory;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenServiceFactory;
use Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenServiceFactory;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenServiceFactory;
use Exdrals\Identity\Infrastructure\Validator\AccountActivationValidator;
use Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator;
use Exdrals\Identity\Infrastructure\Validator\Input\AccountNameInput;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Exdrals\Identity\Infrastructure\Validator\PasswordValidator;
use Exdrals\Identity\Middleware\Account\Authentication\AuthenticationConditionsMiddleware;
use Exdrals\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware;
use Exdrals\Identity\Middleware\Account\LastActivityUpdaterMiddleware;
use Exdrals\Identity\Middleware\Account\RequestAuthenticationMiddleware;
use Exdrals\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware;
use Exdrals\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware;
use Exdrals\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware;
use Exdrals\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware;
use Exdrals\Identity\Middleware\RequireLoginMiddleware;
use Exdrals\Identity\Middleware\Token\AccessTokenValidationMiddleware;
use Exdrals\Identity\Middleware\Token\RefreshTokenAccountMiddleware;
use Exdrals\Identity\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware;
use Exdrals\Identity\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware;
use Exdrals\Identity\Middleware\Token\RefreshTokenValidationMiddleware;
use Exdrals\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\UrlHelper;
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
                EmailService::class => EmailServiceFactory::class,
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
