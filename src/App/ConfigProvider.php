<?php declare(strict_types=1);

namespace App;

use App\Hydrator\AccountAccessAuthHydratorInterface;
use App\Hydrator\AccountActivationHydratorInterface;
use App\Hydrator\AccountHydratorInterface;
use App\Hydrator\TokenHydratorInterface;
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
use App\Table\AccountAccessAuthTable;
use App\Table\AccountActivationTable;
use App\Table\AccountTable;
use App\Table\TokenTable;
use App\Validator\AccountActivationValidator;
use App\Validator\AuthenticationValidator;
use App\Validator\EMailValidator;
use App\Validator\Input\AccountNameInput;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\PasswordValidator;
use Core\Repository\AccountAccessAuthRepositoryInterface;
use Core\Repository\AccountActivationRepositoryInterface;
use Core\Repository\AccountRepositoryInterface;
use Core\Repository\TokenRepositoryInterface;
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
                Repository\AccountAccessAuthRepository::class => ConfigAbstractFactory::class,
                Repository\AccountActivationRepository::class => ConfigAbstractFactory::class,
                Repository\AccountRepository::class => ConfigAbstractFactory::class,
                Repository\TokenRepository::class => ConfigAbstractFactory::class,
                Service\Account\AccountService::class => ConfigAbstractFactory::class,
                Service\Authentication\AuthenticationService::class => InvokableFactory::class,
                Service\ClientIdentification\ClientIdentificationService::class => InvokableFactory::class,
                EmailService::class => EmailServiceFactory::class,
                Service\Token\AccessTokenService::class => Service\Token\AccessTokenServiceFactory::class,
                Service\Token\ActivationTokenService::class => ActivationTokenServiceFactory::class,
                Service\Token\PasswordTokenService::class => PasswordTokenServiceFactory::class,
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
