<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Factory;

use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Infrastructure\Provider\EmailHashSaltProvider;
use Psr\Container\ContainerInterface;

readonly final class EmailHashSaltProviderFactory
{
    public function __invoke(ContainerInterface $container): EmailHashSaltProviderInterface
    {
        /** @var array{logger: array{email_hash_salt: string}} $config */
        $config = $container->get('config');

        return new EmailHashSaltProvider($config['logger']['email_hash_salt']);
    }
}
