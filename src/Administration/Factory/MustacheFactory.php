<?php declare(strict_types=1);

namespace Administration\Factory;

use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use Psr\Container\ContainerInterface;

class MustacheFactory
{
    public function __invoke(ContainerInterface $container): Mustache_Engine
    {
        return new Mustache_Engine([
            'entity_flags' => ENT_QUOTES,
            'delimiters' => '{{+ }}',
        ]);
    }
}
