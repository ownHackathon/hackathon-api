<?php declare(strict_types=1);

namespace ownHackathon\Core;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Psr\Log\LoggerInterface;
use ownHackathon\App\Validator\Input\EmailInput;
use ownHackathon\App\Validator\Input\PasswordInput;
use ownHackathon\Core\Listener\LoggingErrorListener;
use ownHackathon\Core\Listener\LoggingErrorListenerFactory;

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
                LoggingErrorListener::class => LoggingErrorListenerFactory::class,
                Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
        ];
    }
}
