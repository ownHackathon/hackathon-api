<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Factory;

use Psr\Container\ContainerInterface;

readonly final class EmailHashSaltFactory
{
    public function __invoke(ContainerInterface $container): string
    {
        /** @var array{logger: array{email_hash_salt: string}} $config */
        $config = $container->get('config');

        return $config['logger']['email_hash_salt'];
    }
}
