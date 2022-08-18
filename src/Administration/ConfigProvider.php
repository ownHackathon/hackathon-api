<?php declare(strict_types=1);

namespace Administration;

use App\Handler\IndexHandler;
use App\Service\UserService;
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
            ],
            'factories' => [
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
        ];
    }
}
