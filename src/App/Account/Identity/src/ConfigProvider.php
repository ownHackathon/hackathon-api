<?php declare(strict_types=1);

namespace Exdrals\Identity;

use Envms\FluentPDO\Query;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountAccessAuthHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountActivationHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\Account\AccountHydratorInterface;
use Exdrals\Identity\Infrastructure\Hydrator\Token\TokenHydratorInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountAccessAuthStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountActivationStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Account\AccountStoreInterface;
use Exdrals\Identity\Infrastructure\Persistence\Table\Token\TokenStoreInterface;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
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
use Exdrals\Mailing\Infrastructure\Validator\EMailValidator;
use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Log\LoggerInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;

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
                'path' => '/api/token/refresh[/]',
                'middleware' => [
                    Middleware\Token\RefreshTokenValidationMiddleware::class,
                    Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class,
                    Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class,
                    Middleware\Token\RefreshTokenAccountMiddleware::class,
                    Middleware\Token\GenerateAccessTokenMiddleware::class,
                    Handler\AccessTokenHandler::class,
                ],
                'allowed_methods' => ['GET'],
                'name' => 'api_identity_token_refresh',
            ],
            [
                'path' => '/api/account/authentication[/]',
                'middleware' => [
                    Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class,
                    Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class,
                    Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class,
                    Middleware\Token\GenerateRefreshTokenMiddleware::class,
                    Middleware\Token\GenerateAccessTokenMiddleware::class,
                    Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class,
                    Handler\AuthenticationHandler::class,
                ],
                'allowed_methods' => ['POST'],
                'name' => 'api_identity_authentication',
            ],
            [
                'path' => '/api/account',
                'middleware' => [
                    Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    Middleware\Account\RegisterMiddleware::class,
                    Handler\AccountRegisterHandler::class,
                ],
                'allowed_methods' => ['POST'],
                'name' => 'api_identity_register',
            ],
            [
                'path' => '/api/account/activation/[{token}[/]]',
                'middleware' => [
                    Middleware\Account\Validation\ActivationInputValidatorMiddleware::class,
                    Middleware\Account\ActivationMiddleware::class,
                    Handler\AccountActivationHandler::class,
                ],
                'allowed_methods' => ['POST'],
                'name' => 'api_identity_activation',
            ],
            [
                'path' => '/api/account/password/forgotten[/]',
                'middleware' => [
                    Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
                    Middleware\Account\PasswordForgottenMiddleware::class,
                    Handler\AccountPasswordForgottenHandler::class,
                ],
                'allowed_methods' => ['POST'],
                'name' => 'api_identity_password_forgotten',
            ],
            [
                'path' => '/api/account/password/[{token}[/]]',
                'middleware' => [
                    Middleware\Account\Validation\PasswordInputValidatorMiddleware::class,
                    Middleware\Account\PasswordChangeMiddleware::class,
                    Handler\AccountPasswordHandler::class,
                ],
                'allowed_methods' => ['PATCH'],
                'name' => 'api_identity_password_change',
            ],
            [
                'path' => '/api/account/logout[/]',
                'middleware' => [
                    Middleware\Token\AccessTokenValidationMiddleware::class,
                    Middleware\Account\LogoutMiddleware::class,
                    Handler\LogoutHandler::class,
                ],
                'allowed_methods' => ['GET'],
                'name' => 'api_identity_logout',
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                Infrastructure\Hydrator\Account\AccountAccessAuthHydratorInterface::class => Infrastructure\Hydrator\Account\AccountAccessAuthHydrator::class,
                Infrastructure\Hydrator\Account\AccountActivationHydratorInterface::class => Infrastructure\Hydrator\Account\AccountActivationHydrator::class,
                Infrastructure\Hydrator\Account\AccountHydratorInterface::class => Infrastructure\Hydrator\Account\AccountHydrator::class,
                Infrastructure\Hydrator\Token\TokenHydratorInterface::class => Infrastructure\Hydrator\Token\TokenHydrator::class,

                Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountRepository::class,
                Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountActivationRepository::class,
                Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepositoryInterface::class => Infrastructure\Persistence\Repository\Account\AccountAccessAuthRepository::class,
                Infrastructure\Persistence\Repository\Token\TokenRepositoryInterface::class => Infrastructure\Persistence\Repository\Token\TokenRepository::class,
                Infrastructure\Persistence\Table\Account\AccountStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountTable::class,
                Infrastructure\Persistence\Table\Account\AccountAccessAuthStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountAccessAuthTable::class,
                Infrastructure\Persistence\Table\Account\AccountActivationStoreInterface::class => Infrastructure\Persistence\Table\Account\AccountActivationTable::class,
                Infrastructure\Persistence\Table\Token\TokenStoreInterface::class => Infrastructure\Persistence\Table\Token\TokenTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                Infrastructure\Hydrator\Account\AccountAccessAuthHydrator::class => InvokableFactory::class,
                Infrastructure\Hydrator\Account\AccountActivationHydrator::class => ConfigAbstractFactory::class,
                Infrastructure\Hydrator\Account\AccountHydrator::class => ConfigAbstractFactory::class,
                Infrastructure\Hydrator\Token\TokenHydrator::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class => InvokableFactory::class,
                Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\ActivationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LastAktivityUpdaterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LogoutMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\PasswordChangeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\PasswordForgottenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\RegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\RequestAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ClientIdentification\ClientIdentificationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\AccessTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\GenerateAccessTokenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\GenerateRefreshTokenMiddleware::class => ConfigAbstractFactory::class,
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
                \Exdrals\Mailing\Infrastructure\EmailService::class => \Exdrals\Mailing\Infrastructure\EmailServiceFactory::class,
                Infrastructure\Service\Token\AccessTokenService::class => Infrastructure\Service\Token\AccessTokenServiceFactory::class,
                Infrastructure\Service\Token\ActivationTokenService::class => Infrastructure\Service\Token\ActivationTokenServiceFactory::class,
                Infrastructure\Service\Token\PasswordTokenService::class => Infrastructure\Service\Token\PasswordTokenServiceFactory::class,
                Infrastructure\Service\Token\RefreshTokenService::class => Infrastructure\Service\Token\RefreshTokenServiceFactory::class,
                Infrastructure\Persistence\Table\Account\AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Account\AccountActivationTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Account\AccountTable::class => ConfigAbstractFactory::class,
                Infrastructure\Persistence\Table\Token\TokenTable::class => ConfigAbstractFactory::class,

                \Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput::class => InvokableFactory::class,
                \Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput::class => InvokableFactory::class,
                \Exdrals\Identity\Infrastructure\Validator\Input\AccountNameInput::class => InvokableFactory::class,
                Infrastructure\Validator\AccountActivationValidator::class => ConfigAbstractFactory::class,
                Infrastructure\Validator\AuthenticationValidator::class => ConfigAbstractFactory::class,
                \Exdrals\Mailing\Infrastructure\Validator\EMailValidator::class => ConfigAbstractFactory::class,
                Infrastructure\Validator\PasswordValidator::class => ConfigAbstractFactory::class,
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
            Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class => [
                AuthenticationService::class,
                AccountRepositoryInterface::class,
            ],
            Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class => [
                AuthenticationValidator::class,
            ],
            Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
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
            Middleware\Account\ActivationMiddleware::class => [
                AccountActivationRepositoryInterface::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
            ],
            Middleware\Account\LastAktivityUpdaterMiddleware::class => [
                AccountRepositoryInterface::class,
            ],
            Middleware\Account\LogoutMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
            ],
            Middleware\Account\PasswordChangeMiddleware::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
            ],
            Middleware\Account\PasswordForgottenMiddleware::class => [
                AccountService::class,
            ],
            Middleware\Account\RegisterMiddleware::class => [
                AccountService::class,
                AccountActivationRepositoryInterface::class,
                ActivationTokenService::class,
                UuidFactoryInterface::class,
                LoggerInterface::class,
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
            Middleware\Token\GenerateAccessTokenMiddleware::class => [
                AccessTokenService::class,
            ],
            Middleware\Token\GenerateRefreshTokenMiddleware::class => [
                RefreshTokenService::class,
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
            \Exdrals\Mailing\Infrastructure\Validator\EMailValidator::class => [
                EmailInput::class,
            ],
            Infrastructure\Validator\PasswordValidator::class => [
                PasswordInput::class,
            ],
        ];
    }
}
