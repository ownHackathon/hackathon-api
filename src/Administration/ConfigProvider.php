<?php declare(strict_types=1);

namespace Administration;

use Administration\Service\EMail\TopicCreateEMailService;
use Administration\Service\EMail\TopicCreateEMailServiceFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Symfony\Component\Mailer\Mailer;

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
                TopicCreateEMailService::class => TopicCreateEMailServiceFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
        ];
    }
}
