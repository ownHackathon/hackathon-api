<?php declare(strict_types=1);

namespace Authentication;

use App\Hydrator\ReflectionHydrator;
use App\Service\TokenService;
use App\Service\UserService;
use App\Validator\Input;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\Input\UsernameInput;
use Authentication\Handler\LoginHandlerFactory;
use Authentication\Service\ApiAccessService;
use Authentication\Validator;
use Laminas\Hydrator\ClassMethodsHydrator;
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
                Handler\ApiMeHandler::class,
                Handler\UserPasswordChangeHandler::class,
                Handler\UserPasswordVerifyTokenHandler::class,
                Handler\UserRegisterSubmitHandler::class,
                Middleware\IsLoggedInAuthenticationMiddleware::class,
                Middleware\LoginValidationMiddleware::class,
                Service\LoginAuthenticationService::class,
            ],
            'factories' => [
                Handler\LoginHandler::class => LoginHandlerFactory::class,
                Handler\UserPasswordForgottonHandler::class => Handler\UserPasswordForgottonHandlerFactory::class,

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

                Service\ApiAccessService::class => Service\ApiAccessServiceFactory::class,

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
                ClassMethodsHydrator::class,
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
                Service\LoginAuthenticationService::class,
            ],
            Middleware\LoginValidationMiddleware::class => [
                Validator\LoginValidator::class,
            ],

            Validator\LoginValidator::class => [
                Input\UsernameInput::class,
                Input\PasswordInput::class,
            ],
            Validator\PasswordForgottenEmailValidator::class => [
                Input\EmailInput::class,
            ],
            Validator\RegisterValidator::class => [
                UsernameInput::class,
                PasswordInput::class,
                EmailInput::class,
            ],
            Validator\UserPasswordChangeValidator::class => [
                PasswordInput::class,
            ],
        ];
    }
}
