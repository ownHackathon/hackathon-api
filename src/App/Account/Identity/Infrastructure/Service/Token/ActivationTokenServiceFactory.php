<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use ownHackathon\App\Mailing\Application\Port\MailerInterface;
use Psr\Container\ContainerInterface;

readonly class ActivationTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): ActivationTokenService
    {
        $emailService = $container->get(MailerInterface::class);
        $projectUri = $container->get('config')['project']['uri'];

        return new ActivationTokenService($emailService, $projectUri);
    }
}
