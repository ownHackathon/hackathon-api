<?php declare(strict_types=1);

namespace App;

use App\Hydrator\Account\AccountAccessAuthHydratorInterface;
use App\Hydrator\Account\AccountActivationHydratorInterface;
use App\Hydrator\Account\AccountHydratorInterface;
use App\Hydrator\Token\TokenHydratorInterface;
use App\Hydrator\Workspace\WorkspaceHydratorInterface;
use App\Service\Account\AccountService;
use App\Service\Authentication\AuthenticationService;
use App\Service\ClientIdentification\ClientIdentificationService;
use App\Service\Email\EmailService;
use App\Service\Email\EmailServiceFactory;
use App\Service\Token\AccessTokenService;
use App\Service\Token\ActivationTokenService;
use App\Service\Token\ActivationTokenServiceFactory;
use App\Service\Token\PasswordTokenService;
use App\Service\Token\PasswordTokenServiceFactory;
use App\Service\Token\RefreshTokenService;
use App\Table\Account\AccountAccessAuthTable;
use App\Table\Account\AccountActivationTable;
use App\Table\Account\AccountTable;
use App\Table\Token\TokenTable;
use App\Table\Workspace\WorkspaceTable;
use App\Validator\AccountActivationValidator;
use App\Validator\AuthenticationValidator;
use App\Validator\EMailValidator;
use App\Validator\Input\AccountNameInput;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\PasswordValidator;
use Core\Repository\Account\AccountAccessAuthRepositoryInterface;
use Core\Repository\Account\AccountActivationRepositoryInterface;
use Core\Repository\Account\AccountRepositoryInterface;
use Core\Repository\Token\TokenRepositoryInterface;
use Core\Repository\Workspace\WorkspaceRepositoryInterface;
use Core\Store;
use Core\Utils\UuidFactoryInterface;
use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Log\LoggerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                Hydrator\Account\AccountAccessAuthHydratorInterface::class => Hydrator\Account\AccountAccessAuthHydrator::class,
                Hydrator\Account\AccountActivationHydratorInterface::class => Hydrator\Account\AccountActivationHydrator::class,
                Hydrator\Account\AccountHydratorInterface::class => Hydrator\Account\AccountHydrator::class,
                Hydrator\Token\TokenHydratorInterface::class => Hydrator\Token\TokenHydrator::class,
                Hydrator\Workspace\WorkspaceHydratorInterface::class => Hydrator\Workspace\WorkspaceHydrator::class,

                AccountRepositoryInterface::class => Repository\Account\AccountRepository::class,
                AccountActivationRepositoryInterface::class => Repository\Account\AccountActivationRepository::class,
                AccountAccessAuthRepositoryInterface::class => Repository\Account\AccountAccessAuthRepository::class,
                TokenRepositoryInterface::class => Repository\Token\TokenRepository::class,
                WorkspaceRepositoryInterface::class => Repository\Workspace\WorkspaceRepository::class,

                Store\Account\AccountStoreInterface::class => AccountTable::class,
                Store\Account\AccountAccessAuthStoreInterface::class => AccountAccessAuthTable::class,
                Store\Account\AccountActivationStoreInterface::class => AccountActivationTable::class,
                Store\Token\TokenStoreInterface::class => TokenTable::class,
                Store\Workspace\WorkspaceStoreInterface::class => WorkspaceTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                Hydrator\Account\AccountAccessAuthHydrator::class => InvokableFactory::class,
                Hydrator\Account\AccountActivationHydrator::class => ConfigAbstractFactory::class,
                Hydrator\Account\AccountHydrator::class => ConfigAbstractFactory::class,
                Hydrator\Token\TokenHydrator::class => ConfigAbstractFactory::class,
                Hydrator\Workspace\WorkspaceHydrator::class => InvokableFactory::class,

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

                Repository\Account\AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                Repository\Account\AccountActivationRepository::class => ConfigAbstractFactory::class,
                Repository\Account\AccountRepository::class => ConfigAbstractFactory::class,
                Repository\Token\TokenRepository::class => ConfigAbstractFactory::class,
                Repository\Workspace\WorkspaceRepository::class => ConfigAbstractFactory::class,

                Service\Account\AccountService::class => ConfigAbstractFactory::class,
                Service\Authentication\AuthenticationService::class => InvokableFactory::class,
                Service\ClientIdentification\ClientIdentificationService::class => InvokableFactory::class,
                EmailService::class => EmailServiceFactory::class,
                Service\Token\AccessTokenService::class => Service\Token\AccessTokenServiceFactory::class,
                Service\Token\ActivationTokenService::class => ActivationTokenServiceFactory::class,
                Service\Token\PasswordTokenService::class => PasswordTokenServiceFactory::class,
                Service\Token\RefreshTokenService::class => Service\Token\RefreshTokenServiceFactory::class,

                Table\Account\AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                Table\Account\AccountActivationTable::class => ConfigAbstractFactory::class,
                Table\Account\AccountTable::class => ConfigAbstractFactory::class,
                Table\Token\TokenTable::class => ConfigAbstractFactory::class,
                Table\Workspace\WorkspaceTable::class => ConfigAbstractFactory::class,

                Validator\Input\AccountNameInput::class => InvokableFactory::class,
                Validator\Input\EmailInput::class => InvokableFactory::class,
                Validator\Input\PasswordInput::class => InvokableFactory::class,
                Validator\AccountActivationValidator::class => ConfigAbstractFactory::class,
                Validator\AuthenticationValidator::class => ConfigAbstractFactory::class,
                Validator\EMailValidator::class => ConfigAbstractFactory::class,
                Validator\PasswordValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Hydrator\Account\AccountActivationHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Hydrator\Account\AccountHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Hydrator\Token\TokenHydrator::class => [
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

            Repository\Account\AccountAccessAuthRepository::class => [
                Store\Account\AccountAccessAuthStoreInterface::class,
            ],
            Repository\Account\AccountActivationRepository::class => [
                Store\Account\AccountActivationStoreInterface::class,
            ],
            Repository\Account\AccountRepository::class => [
                Store\Account\AccountStoreInterface::class,
            ],
            Repository\Token\TokenRepository::class => [
                Store\Token\TokenStoreInterface::class,
            ],
            Repository\Workspace\WorkspaceRepository::class => [
                Store\Workspace\WorkspaceStoreInterface::class,
            ],

            Service\Account\AccountService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                PasswordTokenService::class,
                UuidFactoryInterface::class,
            ],
            Table\Account\AccountAccessAuthTable::class => [
                Query::class,
                AccountAccessAuthHydratorInterface::class,
            ],
            Table\Account\AccountActivationTable::class => [
                Query::class,
                AccountActivationHydratorInterface::class,
            ],
            Table\Account\AccountTable::class => [
                Query::class,
                AccountHydratorInterface::class,
            ],
            Table\Token\TokenTable::class => [
                Query::class,
                TokenHydratorInterface::class,
            ],
            Table\Workspace\WorkspaceTable::class => [
                Query::class,
                WorkspaceHydratorInterface::class,
            ],

            Validator\AccountActivationValidator::class => [
                AccountNameInput::class,
                PasswordInput::class,
            ],
            Validator\AuthenticationValidator::class => [
                EmailInput::class,
                PasswordInput::class,
            ],
            Validator\EMailValidator::class => [
                EmailInput::class,
            ],
            Validator\PasswordValidator::class => [
                PasswordInput::class,
            ],
        ];
    }
}
