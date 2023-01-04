<?php declare(strict_types=1);

error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Europe/Berlin');

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require_once __DIR__ . '/../constants.php';

require ROOT_DIR . 'vendor/autoload.php';

(function () {
    /** @var Psr\Container\ContainerInterface $container */
    $container = require CONFIG_DIR . 'container.php';

    /** @var Mezzio\Application $app */
    $app = $container->get(Mezzio\Application::class);

    $factory = $container->get(Mezzio\MiddlewareFactory::class);

    (require CONFIG_DIR . 'pipeline.php')($app);
    (require CONFIG_DIR . 'routes.php')($app);

    $app->run();
})();
