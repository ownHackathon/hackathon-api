<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Token;

use App\Mailing\Api\EmailSendInterface;
use Psr\Container\ContainerInterface;

readonly final class ActivationTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): ActivationTokenService
    {
        $emailService = $container->get(EmailSendInterface::class);

        /** @var array{project: array{uri: string}} $config */
        $config = $container->get('config');
        $projectUri = $config['project']['uri'];

        return new ActivationTokenService($emailService, $projectUri);
    }
}
