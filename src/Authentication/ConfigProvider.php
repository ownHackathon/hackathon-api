<?php declare(strict_types=1);

namespace Authentication;

use App\Hydrator\ReflectionHydrator;
use App\Service\UserService;
use App\Validator\Input;
use App\Validator\Input\EmailInput;
use App\Validator\Input\PasswordInput;
use App\Validator\Input\UsernameInput;
use Authentication\Handler\LoginHandlerFactory;
use Authentication\Validator;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Template\TemplateRendererInterface;

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
                Handler\UserRegisterSubmitHandler::class,
                Middleware\IsLoginAuthenticationMiddleware::class,
                Middleware\LoginValidationMiddleware::class,
                Service\LoginAuthenticationService::class,
            ],
            'factories' => [
                Handler\ApiMeHandler::class => ConfigAbstractFactory::class,
                Handler\LoginHandler::class => LoginHandlerFactory::class,

                Middleware\JwtAuthenticationMiddleware::class => Middleware\JwtAuthenticationMiddlewareFactory::class,
                Middleware\LoginAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\LoginValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserRegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserRegisterValidationMiddleware::class => ConfigAbstractFactory::class,

                Validator\LoginValidator::class => ConfigAbstractFactory::class,
                Validator\RegisterValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\ApiMeHandler::class => [
                ReflectionHydrator::class,
            ],
            Middleware\UserRegisterMiddleware::class => [
                UserService::class,
                ClassMethodsHydrator::class,
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
            Validator\RegisterValidator::class => [
                UsernameInput::class,
                PasswordInput::class,
                EmailInput::class,
            ],
        ];
    }
}
