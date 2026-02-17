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
use Exdrals\Identity\Handler\AccountPasswordForgottenHandler;
use Exdrals\Identity\Handler\AccountPasswordHandler;
use Exdrals\Identity\Handler\AccountRegisterHandler;
use Exdrals\Identity\Handler\AuthenticationHandler;
use Exdrals\Identity\Handler\LogoutHandler;
use Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\AccountHydratorInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\AccountStoreInterface;
use Exdrals\Identity\Infrastructure\Service\Account\AccountAuthenticationService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountCreatorService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountRegisterService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordChangeService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordService;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Exdrals\Identity\Infrastructure\Validator\AccountActivationValidator;
use Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator;
use Exdrals\Identity\Infrastructure\Validator\Input\AccountNameInput;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Exdrals\Identity\Infrastructure\Validator\PasswordValidator;
use Exdrals\Identity\Middleware\RequireLoginMiddleware;
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
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    \Exdrals\Identity\Handler\AccountRegisterHandler::class,
                ],
                'name' => 'api_identity_register',
            ],
            [
                'path' => '/api/account/activation/[{token}[/]]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware::class,
                    FluentTransactionMiddleware::class,
                    \Exdrals\Identity\Handler\AccountActivationHandler::class,
                ],
                'name' => 'api_identity_activation',
            ],

            [
                'path' => '/api/account/authentication[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Authentication\AuthenticationConditionsMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware::class,
                    \Exdrals\Identity\Handler\AuthenticationHandler::class,
                ],
                'name' => 'api_identity_authentication',
            ],
            [
                'path' => '/api/token/refresh[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Token\RefreshTokenValidationMiddleware::class,
                    \Exdrals\Identity\Handler\AccessTokenHandler::class,
                ],
                'name' => 'api_identity_token_refresh',
            ],

            [
                'path' => '/api/account/password/forgotten[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    \Exdrals\Identity\Handler\AccountPasswordForgottenHandler::class,
                ],
                'name' => 'api_identity_password_forgotten',
            ],
            [
                'path' => '/api/account/password/[{token}[/]]',
                'allowed_methods' => ['PATCH'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    \Exdrals\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware::class,
                    \Exdrals\Identity\Handler\AccountPasswordHandler::class,
                ],
                'name' => 'api_identity_password_change',
            ],

            [
                'path' => '/api/account/logout[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
                    RequireLoginMiddleware::class,
                    \Exdrals\Identity\Middleware\Token\AccessTokenValidationMiddleware::class,
                    \Exdrals\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware::class,
                    \Exdrals\Identity\Handler\LogoutHandler::class,
                ],
                'name' => 'api_identity_logout',
            ],
            [
                'path' => '/api/account/[{accountUuid:[0-9a-fA-F\-]+}[/]]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    \Exdrals\Identity\Middleware\IdentityExceptionMappingMiddleware::class,
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
                AccountAccessAuthHydratorInterface::class => \Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator::class,
                AccountActivationHydratorInterface::class => \Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydrator::class,
                AccountHydratorInterface::class => \Exdrals\Identity\Infrastructure\Hydrator\AccountHydrator::class,

                AccountRepositoryInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepository::class,
                AccountActivationRepositoryInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository::class,
                AccountAccessAuthRepositoryInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository::class,
                AccountStoreInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Table\AccountTable::class,
                AccountAccessAuthStoreInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable::class,
                AccountActivationStoreInterface::class => \Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                \Exdrals\Identity\Infrastructure\Hydrator\AccountAccessAuthHydrator::class => InvokableFactory::class,
                \Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydrator::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Hydrator\AccountHydrator::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\Authentication\AuthenticationConditionsMiddleware::class => InvokableFactory::class,
                \Exdrals\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\LastActivityUpdaterMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Account\PasswordChangeService::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Account\RequestAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Token\AccessTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Token\RefreshTokenAccountMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class => InvokableFactory::class,
                \Exdrals\Identity\Middleware\Token\RefreshTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepository::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Account\AccountService::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService::class => InvokableFactory::class,
                \Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService::class => InvokableFactory::class,
                EmailService::class => EmailServiceFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService::class => \Exdrals\Identity\Infrastructure\Service\Token\AccessTokenServiceFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService::class => \Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenServiceFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenService::class => \Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenServiceFactory::class,
                \Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService::class => \Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenServiceFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationTable::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Persistence\Table\AccountTable::class => ConfigAbstractFactory::class,

                EmailInput::class => InvokableFactory::class,
                PasswordInput::class => InvokableFactory::class,
                AccountNameInput::class => InvokableFactory::class,
                \Exdrals\Identity\Infrastructure\Validator\AccountActivationValidator::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator::class => ConfigAbstractFactory::class,
                EMailValidator::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Infrastructure\Validator\PasswordValidator::class => ConfigAbstractFactory::class,
                AuthenticationHandler::class => ConfigAbstractFactory::class,
                AccessTokenHandler::class => ConfigAbstractFactory::class,
                AccountAuthenticationService::class => ConfigAbstractFactory::class,
                AccountRegisterHandler::class => ConfigAbstractFactory::class,
                AccountRegisterService::class => ConfigAbstractFactory::class,
                \Exdrals\Identity\Handler\AccountActivationHandler::class => ConfigAbstractFactory::class,
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
            \Exdrals\Identity\Infrastructure\Hydrator\AccountActivationHydrator::class => [
                UuidFactoryInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Hydrator\AccountHydrator::class => [
                UuidFactoryInterface::class,
            ],
            \Exdrals\Identity\Middleware\Account\Authentication\AuthenticationValidationMiddleware::class => [
                AuthenticationValidator::class,
            ],
            \Exdrals\Identity\Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => [
                AccountActivationValidator::class,
            ],
            \Exdrals\Identity\Middleware\Account\Validation\EmailInputValidatorMiddleware::class => [
                EMailValidator::class,
            ],
            \Exdrals\Identity\Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => [
                PasswordValidator::class,
            ],
            \Exdrals\Identity\Middleware\Account\LastActivityUpdaterMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Service\Account\PasswordChangeService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
            ],
            \Exdrals\Identity\Middleware\Account\RequestAuthenticationMiddleware::class => [
                AccessTokenService::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                LoggerInterface::class,
            ],
            \Exdrals\Identity\Middleware\ClientIdentification\ClientIdentificationMiddleware::class => [
                ClientIdentificationService::class,
            ],
            \Exdrals\Identity\Middleware\Token\AccessTokenValidationMiddleware::class => [
                AccessTokenService::class,
            ],
            \Exdrals\Identity\Middleware\Token\RefreshTokenAccountMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            \Exdrals\Identity\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
            ],
            \Exdrals\Identity\Middleware\Token\RefreshTokenValidationMiddleware::class => [
                RefreshTokenService::class,
            ],
            \Exdrals\Identity\Middleware\Token\RefreshTokenViaBodyValidationMiddleware::class => [
                RefreshTokenService::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepository::class => [
                AccountAccessAuthStoreInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepository::class => [
                AccountActivationStoreInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepository::class => [
                AccountStoreInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Service\Account\AccountService::class => [
                AccountRepositoryInterface::class,
                AccountAccessAuthRepositoryInterface::class,
                TokenRepositoryInterface::class,
                PasswordTokenService::class,
                UuidFactoryInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Table\AccountAccessAuthTable::class => [
                Query::class,
                AccountAccessAuthHydratorInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Table\AccountActivationTable::class => [
                Query::class,
                AccountActivationHydratorInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Persistence\Table\AccountTable::class => [
                Query::class,
                AccountHydratorInterface::class,
            ],
            \Exdrals\Identity\Infrastructure\Validator\AccountActivationValidator::class => [
                AccountNameInput::class,
                PasswordInput::class,
            ],
            \Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator::class => [
                EmailInput::class,
                PasswordInput::class,
            ],
            EMailValidator::class => [
                EmailInput::class,
            ],
            \Exdrals\Identity\Infrastructure\Validator\PasswordValidator::class => [
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
            \Exdrals\Identity\Handler\AccountActivationHandler::class => [
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
