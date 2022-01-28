<?php declare(strict_types=1);

namespace Administration\Service;

use Psr\Container\ContainerInterface;

class TemplateServiceFactory
{
    public function __invoke(ContainerInterface $container): TemplateService
    {
        $settings = $container->get('config');
        $settings = $settings['templates'];

        return new TemplateService($settings);
    }
}
