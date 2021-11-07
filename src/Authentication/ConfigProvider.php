<?php declare(strict_types=1);

namespace Authentication;

use App\Service\UserService;
use App\Validator\Input;
use Authentication\Handler\LoginHandlerFactory;
use Authentication\Validator;
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
                Middleware\IsLoginAuthenticationMiddleware::class,
                Middleware\LoginValidationMiddleware::class,
                Service\LoginAuthenticationService::class,
            ],
            'factories' => [
                Handler\LoginHandler::class => LoginHandlerFactory::class,

                Middleware\JwtAuthenticationMiddleware::class => Middleware\JwtAuthenticationMiddlewareFactory::class,
                Middleware\LoginAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\LoginValidationMiddleware::class => ConfigAbstractFactory::class,

                Validator\LoginValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
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
        ];
    }
}
