<?php declare(strict_types=1);

namespace App\Mailing;

use App\Mailing\Infrastructure\Validator\Input\EmailInput;
use App\Mailing\Infrastructure\Factory\MailFactory;
use App\Mailing\Infrastructure\Factory\EmailServiceFactory;
use App\Mailing\Infrastructure\Service\EmailService;
use App\Mailing\Application\Port\MailerInterface;
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
                MailerInterface::class => EmailService::class,

            ],
            'factories' => [
                'mailer' => MailFactory::class,
                EmailService::class => EmailServiceFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [];
    }
}
