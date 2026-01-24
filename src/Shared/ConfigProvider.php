<?php declare(strict_types=1);

namespace Shared;

use Exdrals\Account\Identity\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Account\Identity\Infrastructure\Validator\Input\PasswordInput;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
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
            'invokables' => [
                EmailInput::class => EmailInput::class,
                PasswordInput::class => PasswordInput::class,
            ],
            'aliases' => [
            ],
            'factories' => [
                Infrastructure\Factory\ErrorResponseFactory::class => ConfigAbstractFactory::class,
                Middleware\ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,

            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Infrastructure\Factory\ErrorResponseFactory::class => [
                LoggerInterface::class,
            ],
            Middleware\ApiErrorHandlerMiddleware::class => [
                Infrastructure\Factory\ErrorResponseFactory::class,
            ],
            Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
        ];
    }
}
