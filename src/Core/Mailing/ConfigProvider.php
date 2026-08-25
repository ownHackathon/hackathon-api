<?php declare(strict_types=1);

namespace Exdrals\Core\Mailing;

use Exdrals\Core\Mailing\Infrastructure\Validator\Input\EmailInput;
use Exdrals\Core\Shared\Infrastructure\Factory\MailFactory;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

readonly class ConfigProvider
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
            ],
            'aliases' => [
                \Symfony\Component\Mailer\MailerInterface::class => 'mailer',

            ],
            'factories' => [
                'mailer' => MailFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [];
    }
}
