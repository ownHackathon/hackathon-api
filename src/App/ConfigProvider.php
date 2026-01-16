<?php declare(strict_types=1);

namespace ownHackathon\App;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Log\LoggerInterface;
use ownHackathon\App\Hydrator\AccountAccessAuthHydratorInterface;
use ownHackathon\App\Hydrator\AccountActivationHydratorInterface;
use ownHackathon\App\Hydrator\AccountHydratorInterface;
use ownHackathon\App\Hydrator\TokenHydratorInterface;
use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\App\Service\ClientIdentification\ClientIdentificationService;
use ownHackathon\App\Service\Token\AccessTokenService;
use ownHackathon\App\Service\Token\ActivationTokenService;
use ownHackathon\App\Service\Token\PasswordTokenService;
use ownHackathon\App\Service\Token\RefreshTokenService;
use ownHackathon\App\Table\AccountAccessAuthTable;
use ownHackathon\App\Table\AccountActivationTable;
use ownHackathon\App\Table\AccountTable;
use ownHackathon\App\Table\TokenTable;
use ownHackathon\App\Validator\AccountActivationValidator;
use ownHackathon\App\Validator\AuthenticationValidator;
use ownHackathon\App\Validator\EMailValidator;
use ownHackathon\App\Validator\Input\AccountNameInput;
use ownHackathon\App\Validator\Input\EmailInput;
use ownHackathon\App\Validator\Input\PasswordInput;
use ownHackathon\App\Validator\PasswordValidator;
use ownHackathon\Core\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Repository\TokenRepositoryInterface;
use ownHackathon\Core\Store;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use Symfony\Component\Mailer\MailerInterface;

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
                Hydrator\AccountAccessAuthHydratorInterface::class => Hydrator\AccountAccessAuthHydrator::class,
                Hydrator\AccountActivationHydratorInterface::class => Hydrator\AccountActivationHydrator::class,
                Hydrator\AccountHydratorInterface::class => Hydrator\AccountHydrator::class,
                Hydrator\TokenHydratorInterface::class => Hydrator\TokenHydrator::class,

                AccountRepositoryInterface::class => Repository\AccountRepository::class,
                AccountActivationRepositoryInterface::class => Repository\AccountActivationRepository::class,
                AccountAccessAuthRepositoryInterface::class => Repository\AccountAccessAuthRepository::class,
                TokenRepositoryInterface::class => Repository\TokenRepository::class,

                Store\AccountStoreInterface::class => AccountTable::class,
                Store\AccountAccessAuthStoreInterface::class => AccountAccessAuthTable::class,
                Store\AccountActivationStoreInterface::class => AccountActivationTable::class,
                Store\TokenStoreInterface::class => TokenTable::class,
            ],
            'invokables' => [
            ],
            'factories' => [
                Hydrator\AccountAccessAuthHydrator::class => InvokableFactory::class,
                Hydrator\AccountActivationHydrator::class => ConfigAbstractFactory::class,
                Hydrator\AccountHydrator::class => ConfigAbstractFactory::class,
                Hydrator\TokenHydrator::class => ConfigAbstractFactory::class,

                Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\EmailInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\ActivationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\PasswordChangeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\PasswordForgottenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\RegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Account\RequestAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ClientIdentification\ClientIdentificationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\GenerateAccessTokenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\GenerateRefreshTokenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenAccountMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class => InvokableFactory::class,
                Middleware\Token\RefreshTokenValidationMiddleware::class => ConfigAbstractFactory::class,
                Repository\AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                Repository\AccountActivationRepository::class => ConfigAbstractFactory::class,
                Repository\AccountRepository::class => ConfigAbstractFactory::class,
                Repository\TokenRepository::class => ConfigAbstractFactory::class,
                Service\Account\AccountService::class => ConfigAbstractFactory::class,
                Service\Authentication\AuthenticationService::class => InvokableFactory::class,
                Service\ClientIdentification\ClientIdentificationService::class => InvokableFactory::class,
                Service\Token\AccessTokenService::class => Service\Token\AccessTokenServiceFactory::class,
                Service\Token\ActivationTokenService::class => ConfigAbstractFactory::class,
                Service\Token\PasswordTokenService::class => ConfigAbstractFactory::class,
                Service\Token\RefreshTokenService::class => Service\Token\RefreshTokenServiceFactory::class,
                Table\AccountAccessAuthTable::class => ConfigAbstractFactory::class,
                Table\AccountActivationTable::class => ConfigAbstractFactory::class,
                Table\AccountTable::class => ConfigAbstractFactory::class,
                Table\TokenTable::class => ConfigAbstractFactory::class,

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
            Hydrator\AccountActivationHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Hydrator\AccountHydrator::class => [
                UuidFactoryInterface::class,
            ],
            Hydrator\TokenHydrator::class => [
                UuidFactoryInterface::class,
            ],

            Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class => [
                LoggerInterface::class,
            ],
            Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class => [
                AuthenticationService::class,
                AccountRepositoryInterface::class,
                LoggerInterface::class,
            ],
            Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class => [
                AuthenticationValidator::class,
                LoggerInterface::class,
            ],
            Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class => [
                AccountAccessAuthRepositoryInterface::class,
                LoggerInterface::class,
            ],
            Middleware\Account\Validation\ActivationInputValidatorMiddleware::class => [
                AccountActivationValidator::class,
                LoggerInterface::class,
            ],
            Middleware\Account\Validation\EmailInputValidatorMiddleware::class => [
                EMailValidator::class,
                LoggerInterface::class,
            ],
            Middleware\Account\Validation\PasswordInputValidatorMiddleware::class => [
                PasswordValidator::class,
                LoggerInterface::class,
            ],
            Middleware\Account\ActivationMiddleware::class => [
                AccountActivationRepositoryInterface::class,
                AccountRepositoryInterface::class,
                UuidFactoryInterface::class,
                LoggerInterface::class,
            ],
            Middleware\Account\PasswordChangeMiddleware::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                AccountService::class,
                LoggerInterface::class,
            ],
            Middleware\Account\PasswordForgottenMiddleware::class => [
                AccountService::class,
            ],
            Middleware\Account\RegisterMiddleware::class => [
                AccountService::class,
                AccountActivationRepositoryInterface::class,
                ActivationTokenService::class,
                UuidFactoryInterface::class,
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

            Repository\AccountAccessAuthRepository::class => [
                Store\AccountAccessAuthStoreInterface::class,
            ],
            Repository\AccountActivationRepository::class => [
                Store\AccountActivationStoreInterface::class,
            ],
            Repository\AccountRepository::class => [
                Store\AccountStoreInterface::class,
            ],
            Repository\TokenRepository::class => [
                Store\TokenStoreInterface::class,
            ],

            Service\Account\AccountService::class => [
                AccountRepositoryInterface::class,
                TokenRepositoryInterface::class,
                PasswordTokenService::class,
                UuidFactoryInterface::class,
            ],
            Service\Token\ActivationTokenService::class => [
                MailerInterface::class,
            ],
            Service\Token\PasswordTokenService::class => [
                MailerInterface::class,
            ],
            Table\AccountAccessAuthTable::class => [
                Query::class,
                AccountAccessAuthHydratorInterface::class,
            ],
            Table\AccountActivationTable::class => [
                Query::class,
                AccountActivationHydratorInterface::class,
            ],
            Table\AccountTable::class => [
                Query::class,
                AccountHydratorInterface::class,
            ],
            Table\TokenTable::class => [
                Query::class,
                TokenHydratorInterface::class,
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
