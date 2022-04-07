<?php declare(strict_types=1);

namespace Administration\Service;

use Mustache_Engine;
use Psr\Container\ContainerInterface;

class TemplateServiceFactory
{
    public function __invoke(ContainerInterface $container): TemplateService
    {
        $mustache = $container->get(Mustache_Engine::class);
        $settings = $container->get('config');
        $settings = $settings['templates'];

        return new TemplateService($mustache, $settings);
    }
}
