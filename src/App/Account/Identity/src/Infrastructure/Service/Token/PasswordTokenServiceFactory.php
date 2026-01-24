<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Token;

use Exdrals\Account\Identity\Infrastructure\Service\Email\EmailService;
use Psr\Container\ContainerInterface;

readonly class PasswordTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): PasswordTokenService
    {
        $emailService = $container->get(EmailService::class);
        $projectUri = $container->get('config')['project']['uri'];

        return new PasswordTokenService($emailService, $projectUri);
    }
}
