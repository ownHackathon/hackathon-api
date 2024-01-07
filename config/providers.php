<?php declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

return new ConfigAggregator([
    Mezzio\ConfigProvider::class,
    Mezzio\Helper\ConfigProvider::class,
    Mezzio\Router\ConfigProvider::class,
    Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    Laminas\HttpHandlerRunner\ConfigProvider::class,
    Laminas\Diactoros\ConfigProvider::class,
    Laminas\Hydrator\ConfigProvider::class,

    // Swoole config to overwrite some services (if installed)
    class_exists(Mezzio\Swoole\ConfigProvider::class)
        ? Mezzio\Swoole\ConfigProvider::class
        : function (): array {
        return [];
    },

    Administration\ConfigProvider::class,
    App\ConfigProvider::class,
    Authentication\ConfigProvider::class,

    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php.dist'),

    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
]);
