<?php declare(strict_types=1);

namespace App\Mailing;

use App\Mailing\Api\EmailSendInterface;
use App\Mailing\Application\EmailSendService;
use App\Mailing\Infrastructure\Factory\EmailServiceFactory;
use App\Mailing\Infrastructure\Factory\MailFactory;
use App\Mailing\Infrastructure\Service\EmailService;
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
            'aliases' => [
                \Symfony\Component\Mailer\MailerInterface::class => 'mailer',
                EmailSendInterface::class => EmailSendService::class,
            ],
            'factories' => [
                'mailer' => MailFactory::class,
                EmailService::class => EmailServiceFactory::class,
                EmailSendService::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            EmailSendService::class => [
                EmailService::class,
            ],
        ];
    }
}
