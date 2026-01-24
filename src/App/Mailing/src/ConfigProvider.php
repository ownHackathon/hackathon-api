<?php declare(strict_types=1);

namespace Exdrals\Mailing;

use Exdrals\Identity\Infrastructure\Validator\Input\PasswordInput;
use Exdrals\Mailing\Infrastructure\Validator\Input\EmailInput;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Exdrals\Shared\Infrastructure\Factory\MailFactory;

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
                PasswordInput::class => PasswordInput::class,
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
