<?php declare(strict_types=1);

namespace Core;

use App\Service\User\UserService;
use Core\Authentication\Handler;
use Core\Authentication\Handler\LoginHandlerFactory;
use Core\Authentication\Handler\UserPasswordForgottonHandlerFactory;
use Core\Authentication\Middleware;
use Core\Authentication\Service;
use Core\Authentication\Service\ApiAccessService;
use Core\Authentication\Service\ApiAccessServiceFactory;
use Core\Authentication\Service\LoginAuthenticationService;
use Core\Hydrator\ClassMethodsHydratorFactory;
use Core\Hydrator\DateTimeFormatterStrategyFactory;
use Core\Hydrator\DateTimeImmutableFormatterStrategyFactory;
use Core\Hydrator\NullableStrategyFactory;
use Core\Hydrator\ReflectionHydrator;
use Core\Listener\LoggingErrorListener;
use Core\Listener\LoggingErrorListenerFactory;
use Core\Repository\UserRepository;
use Core\Table\UserTable;
use Core\Token\TokenService;
use Envms\FluentPDO\Query;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\DateTimeImmutableFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

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
            'invokables' => [
                ReflectionHydrator::class,

                Validator\Input\EmailInput::class,
                Validator\Input\PasswordInput::class,
                Validator\Input\UsernameInput::class,
            ],
            'aliases' => [
                UserRepository::class => UserTable::class,
            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                DateTimeFormatterStrategy::class => DateTimeFormatterStrategyFactory::class,
                DateTimeImmutableFormatterStrategy::class => DateTimeImmutableFormatterStrategyFactory::class,

                Handler\LoginHandler::class => LoginHandlerFactory::class,
                Handler\UserPasswordForgottonHandler::class => UserPasswordForgottonHandlerFactory::class,

                LoggingErrorListener::class => LoggingErrorListenerFactory::class,

                Middleware\ApiAccessMiddleware::class => ConfigAbstractFactory::class,
                Middleware\JwtAuthenticationMiddleware::class => Middleware\JwtAuthenticationMiddlewareFactory::class,
                Middleware\LoginAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\LoginValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserPasswordChangeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserPasswordChangeValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserPasswordForgottenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserPasswordForgottenValidator::class => ConfigAbstractFactory::class,
                Middleware\UserPasswordVerifyTokenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserRegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserRegisterValidationMiddleware::class => ConfigAbstractFactory::class,

                NullableStrategy::class => NullableStrategyFactory::class,

                Service\ApiAccessService::class => ApiAccessServiceFactory::class,

                Table\UserTable::class => ConfigAbstractFactory::class,

                Validator\LoginValidator::class => ConfigAbstractFactory::class,
                Validator\PasswordForgottenEmailValidator::class => ConfigAbstractFactory::class,
                Validator\RegisterValidator::class => ConfigAbstractFactory::class,
                Validator\UserPasswordChangeValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Middleware\UserRegisterMiddleware::class => [
                UserService::class,
                ReflectionHydrator::class,
            ],
            Middleware\ApiAccessMiddleware::class => [
                ApiAccessService::class,
            ],
            Middleware\UserPasswordForgottenMiddleware::class => [
                UserService::class,
                TokenService::class,
            ],
            Middleware\UserPasswordChangeMiddleware::class => [
                UserService::class,
            ],
            Middleware\UserPasswordChangeValidatorMiddleware::class => [
                Validator\UserPasswordChangeValidator::class,
            ],
            Middleware\UserPasswordForgottenValidator::class => [
                Validator\PasswordForgottenEmailValidator::class,
            ],
            Middleware\UserPasswordVerifyTokenMiddleware::class => [
                UserService::class,
            ],
            Middleware\UserRegisterValidationMiddleware::class => [
                Validator\RegisterValidator::class,
            ],
            Middleware\LoginAuthenticationMiddleware::class => [
                UserService::class,
                LoginAuthenticationService::class,
            ],
            Middleware\LoginValidationMiddleware::class => [
                Validator\LoginValidator::class,
            ],

            Table\UserTable::class => [
                Query::class,
            ],

            Validator\LoginValidator::class => [
                Validator\Input\UsernameInput::class,
                Validator\Input\PasswordInput::class,
            ],
            Validator\PasswordForgottenEmailValidator::class => [
                Validator\Input\EmailInput::class,
            ],
            Validator\RegisterValidator::class => [
                Validator\Input\EmailInput::class,
            ],
            Validator\UserPasswordChangeValidator::class => [
                Validator\Input\PasswordInput::class,
            ],
        ];
    }
}
