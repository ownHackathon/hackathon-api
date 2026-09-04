<?php declare(strict_types=1);

namespace Core\Http\Factory;

use Core\Http\Handler\SwaggerUIHandler;
use Psr\Container\ContainerInterface;

final readonly class SwaggerUIHandlerFactory
{
    public function __invoke(ContainerInterface $container): SwaggerUIHandler
    {
        /** @var array{swagger_ui?: array{index_file?: string}} $config */
        $config = $container->get('config');

        return new SwaggerUIHandler(
            $config['swagger_ui']['index_file'] ?? '',
        );
    }
}
