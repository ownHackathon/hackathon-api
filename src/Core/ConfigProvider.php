<?php declare(strict_types=1);

namespace ownHackathon\Core;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ownHackathon\App\Validator\Input\EmailInput;
use ownHackathon\App\Validator\Input\PasswordInput;
use ownHackathon\Core\Listener\LoggingErrorListener;
use ownHackathon\Core\Listener\LoggingErrorListenerFactory;
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
                Factory\ErrorResponseFactory::class => ConfigAbstractFactory::class,
                Middleware\ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,

            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Factory\ErrorResponseFactory::class => [
                LoggerInterface::class,
            ],
            Middleware\ApiErrorHandlerMiddleware::class => [
                Factory\ErrorResponseFactory::class,
            ],
            Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
        ];
    }
}
