<?php declare(strict_types=1);

namespace Exdrals\Identity;

use Envms\FluentPDO\Query;
use Exdrals\Identity\Handler\AccessTokenHandler;
use Exdrals\Identity\Handler\AccountPasswordForgottenHandler;
use Exdrals\Identity\Handler\AccountPasswordHandler;
use Exdrals\Identity\Handler\AccountRegisterHandler;
use Exdrals\Identity\Handler\AuthenticationHandler;
use Exdrals\Identity\Handler\LogoutHandler;
use Exdrals\Identity\Infrastructure\Service\Account\AccountAuthenticationService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountCreatorService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountRegisterService;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordChangeService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordService;
use Exdrals\Identity\Infrastructure\Service\Account\PasswordServiceInterface;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Identity\Infrastructure\Service\ClientIdentification\ClientIdentificationService;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\PasswordTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenService;
use Exdrals\Identity\Infrastructure\Service\Token\RefreshTokenServiceInterface;
use Exdrals\Identity\Infrastructure\Validator\AccountActivationValidator;
use Exdrals\Identity\Infrastructure\Validator\AuthenticationValidator;
use Exdrals\Identity\Infrastructure\Validator\Input\AccountNameInput;
use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Exdrals\Identity\Infrastructure\Validator\PasswordValidator;
use Exdrals\Mailing\Infrastructure\EmailService;
use Exdrals\Mailing\Infrastructure\EmailServiceFactory;
use Exdrals\Mailing\Infrastructure\Validator\EMailValidator;
use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Shared\Infrastructure\Hydrator\Account\AccountAccessAuthHydratorInterface;
use Exdrals\Shared\Infrastructure\Hydrator\Account\AccountActivationHydratorInterface;
use Exdrals\Shared\Infrastructure\Hydrator\Account\AccountHydratorInterface;
use Exdrals\Shared\Infrastructure\Hydrator\Token\TokenHydratorInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountAccessAuthStoreInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountActivationStoreInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Account\AccountStoreInterface;
use Exdrals\Shared\Infrastructure\Persistence\Store\Token\TokenStoreInterface;
use Exdrals\Shared\Middleware\FluentTransactionMiddleware;
use Exdrals\Shared\Middleware\RequireLoginMiddleware;
use Exdrals\Shared\Utils\UuidFactoryInterface;
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
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    Handler\AccountRegisterHandler::class,
                ],
                'name' => 'api_identity_register',
            ],
            [
                'path' => '/api/account/activation/[{token}[/]]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Account\Validation\ActivationInputValidatorMiddleware::class,
                    FluentTransactionMiddleware::class,
                    Handler\AccountActivationHandler::class,
                ],
                'name' => 'api_identity_activation',
            ],

            [
                'path' => '/api/account/authentication[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Account\Authentication\AuthenticationConditionsMiddleware::class,
                    Middleware\Account\Authentication\AuthenticationValidationMiddleware::class,
                    Handler\AuthenticationHandler::class,
                ],
                'name' => 'api_identity_authentication',
            ],
            [
                'path' => '/api/token/refresh[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Token\RefreshTokenValidationMiddleware::class,
                    Handler\AccessTokenHandler::class,
                ],
                'name' => 'api_identity_token_refresh',
            ],

            [
                'path' => '/api/account/password/forgotten[/]',
                'allowed_methods' => ['POST'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    Handler\AccountPasswordForgottenHandler::class,
                ],
                'name' => 'api_identity_password_forgotten',
            ],
            [
                'path' => '/api/account/password/[{token}[/]]',
                'allowed_methods' => ['PATCH'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    Middleware\Account\Validation\PasswordInputValidatorMiddleware::class,
                    Handler\AccountPasswordHandler::class,
                ],
                'name' => 'api_identity_password_change',
            ],

            [
                'path' => '/api/account/logout[/]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
                    RequireLoginMiddleware::class,
                    Middleware\Token\AccessTokenValidationMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
                'name' => 'api_identity_logout',
            ],
            [
                'path' => '/api/account/[{accountUuid:[0-9a-fA-F\-]+}[/]]',
                'allowed_methods' => ['GET'],
                'middleware' => [
                    Middleware\IdentityExceptionMappingMiddleware::class,
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
                AccountAccessAuthHydratorInterface::class => Infrastructure\Hydrator\Account\AccountAccessAuthHydrator::class,
                AccountActivationHydratorInterface::class => Infrastructure\Hydrator\Account\AccountActivationHydrator::class,
                AccountHydratorInterface::class => Infrastructure\Hydrator\Account\AccountHydrator::class,
                TokenHydratorInterface::class => Infrastructure\Hydrator\Token\TokenHydrator::class,

                AccountRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountRepository::class,
                AccountActivationRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountActivationRepository::class,
                AccountAccessAuthRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepository::class,
                TokenRepositoryInterface::class => Infrastructure\Persistence\Repository\Token\TokenRepository::class,
                AccountStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountTable::class,
                AccountAccessAuthStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountAccessAuthTable::class,
                AccountActivationStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountActivationTable::class,
                TokenStoreInterface::class => Infrastructure\Persistence\Table\Token\TokenTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                Infrastructure\Hydrator\Account\AccountAccessAuthHydrator::class => InvokableFactory::class,
                Infrastructure\Hydrator\Account\AccountActivationHydrator::class => ConfigAbstractFactory::class,
                Infrastructure\Hydrator\Account\AccountHydrator::class => ConfigAbstractFactory::class,
                Infrastructure\Hydrator\Token\TokenHydrator::class => ConfigAbstractFactory::class,
                Middleware\Account\Authentication\AuthenticationConditionsMiddleware::class => InvokableFactory::class,
                Middleware\Account\Authentication\AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LastActivityUpdaterMiddleware::class => ConfigAbstractFactory::class,
                Infrastructure\Service\Account\PasswordChangeService::class => ConfigAbstractFactory::class,
                Middleware\Account\RequestAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ClientIdentification\ClientIdentificationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\AccessTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenAccountMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class => InvokableFactory::class,
                Middleware\Token\RefreshTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Repository\Account\AccountActivationRepository::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Repository\Account\AccountRepository::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Repository\Token\TokenRepository::class => ConfigAbstractFactory::class,
                Infrastructure\Service\Account\AccountService::class => ConfigAbstractFactory::class,
                Infrastructure\Service\Authentication\AuthenticationService::class => InvokableFactory::class,
                Infrastructure\Service\ClientIdentification\ClientIdentificationService::class => InvokableFactory::class,
                EmailService::class => EmailServiceFactory::class,
                Infrastructure\Service\Token\AccessTokenService::class => Infrastructure\Service\Token\AccessTokenServiceFactory::class,
                Infrastructure\Service\Token\ActivationTokenService::class => Infrastructure\Service\Token\ActivationTokenServiceFactory::class,
                Infrastructure\Service\Token\PasswordTokenService::class => Infrastructure\Service\Token\PasswordTokenServiceFactory::class,
                Infrastructure\Service\Token\RefreshTokenService::class => Infrastructure\Service\Token\RefreshTokenServiceFactory::class,
                Infrastructure\Persistence\Table\Account\AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Account\AccountActivationTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Account\AccountTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Token\TokenTable::class => ConfigAbstractFactory::class,

                EmailInput::class => InvokableFactory::class,
                PasswordInput::class => InvokableFactory::class,
                AccountNameInput::class => InvokableFactory::class,
                Infrastructure\Validator\AccountActivationValidator::class => ConfigAbstractFactory::class,
                Infrastructure\Validator\AuthenticationValidator::class => ConfigAbstractFactory::class,
                EMailValidator::class => ConfigAbstractFactory::class,
                Infrastructure\Validator\PasswordValidator::class => ConfigAbstractFactory::class,
                AuthenticationHandler::class => ConfigAbstractFactory::class,
                AccessTokenHandler::class => ConfigAbstractFactory::class,
                AccountAuthenticationService::class => ConfigAbstractFactory::class,
                AccountRegisterHandler::class => ConfigAbstractFactory::class,
                AccountRegisterService::class => ConfigAbstractFactory::class,
                Handler\AccountActivationHandler::class => ConfigAbstractFactory::class,
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
            Infrastructure\Hydrator\Account\AccountActivationHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Infrastructure\Hydrator\Account\AccountHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Infrastructure\Hydrator\Token\TokenHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Middleware\Account\Authentication\AuthenticationValidationMiddleware::class => [
                AuthenticationValidator::class,
            ],
            Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => [
                AccountActivationValidator::class,
            ],
            Middleware\Account\Validation\EmailInputValidatorMiddleware::class => [
                EMailValidator::class,
            ],
            Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => [
                PasswordValidator::class,
            ],
            Middleware\Account\LastActivityUpdaterMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            Infrastructure\Service\Account\PasswordChangeService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
            ],
            Middleware\Account\RequestAuthenticationMiddleware::class => [
                AccessTokenService::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                LoggerInterface::class,
            ],
            Middleware\ClientIdentification\ClientIdentificationMiddleware::class => [
                ClientIdentificationService::class,
            ],
            Middleware\Token\AccessTokenValidationMiddleware::class => [
                AccessTokenService::class,
            ],
            Middleware\Token\RefreshTokenAccountMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
            ],
            Middleware\Token\RefreshTokenValidationMiddleware::class => [
                RefreshTokenService::class,
            ],
            Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepository::class => [
                AccountAccessAuthStoreInterface::class,
            ],
            Infrastructure\Persistence\Repository\Account\AccountActivationRepository::class => [
                AccountActivationStoreInterface::class,
            ],
            Infrastructure\Persistence\Repository\Account\AccountRepository::class => [
                AccountStoreInterface::class,
            ],
            Infrastructure\Persistence\Repository\Token\TokenRepository::class => [
                TokenStoreInterface::class,
            ],
            Infrastructure\Service\Account\AccountService::class => [
                AccountRepositoryInterface::class,
                AccountAccessAuthRepositoryInterface::class,
                TokenRepositoryInterface::class,
                PasswordTokenService::class,
                UuidFactoryInterface::class,
            ],
            Infrastructure\Persistence\Table\Account\AccountAccessAuthTable::class => [
                Query::class,
                AccountAccessAuthHydratorInterface::class,
            ],
            Infrastructure\Persistence\Table\Account\AccountActivationTable::class => [
                Query::class,
                AccountActivationHydratorInterface::class,
            ],
            Infrastructure\Persistence\Table\Account\AccountTable::class => [
                Query::class,
                AccountHydratorInterface::class,
            ],
            Infrastructure\Persistence\Table\Token\TokenTable::class => [
                Query::class,
                TokenHydratorInterface::class,
            ],
            Infrastructure\Validator\AccountActivationValidator::class => [
                AccountNameInput::class,
                PasswordInput::class,
            ],
            Infrastructure\Validator\AuthenticationValidator::class => [
                EmailInput::class,
                PasswordInput::class,
            ],
            EMailValidator::class => [
                EmailInput::class,
            ],
            Infrastructure\Validator\PasswordValidator::class => [
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
            Handler\AccountActivationHandler::class => [
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
