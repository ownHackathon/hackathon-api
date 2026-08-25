<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Logger;

use DateTime;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function is_dir;
use function mkdir;
use function rtrim;

readonly class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $path = $container->get('config')['logger']['path'];

        $date = (new DateTime())->format('Y-m-d');
        $path = rtrim($path, '/') . '/' . $date . '/';

        if (!is_dir($path)) {
            mkdir($path, 0775);
        }

        $dateFormat = 'Y-m-d H:i:s';
        $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        $stackTraceFormater = clone $formatter;
        $stackTraceFormater->includeStacktraces(true);

        $logger = new Logger('log');

        $logger->pushHandler(new StreamHandler($path . 'default.log')->setFormatter($formatter));

        $errorHandler = new StreamHandler($path . 'error.log', Level::Error)->setFormatter($formatter);
        $errorHandler = new FilterHandler($errorHandler, Level::Error, Level::Error);

        $logger->pushHandler($errorHandler);

        $errorHandler = new StreamHandler($path . 'warning.log', Level::Warning)->setFormatter($formatter);
        $errorHandler = new FilterHandler($errorHandler, Level::Error, Level::Error);

        $logger->pushHandler($errorHandler);

        $logger->pushHandler(
            new StreamHandler($path . 'critical.log', Level::Critical)->setFormatter($stackTraceFormater)
        );
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $logger->pushProcessor(
            new MetaDataProcessor(
                filter_input(INPUT_SERVER, 'REMOTE_ADDR'),
                filter_input(INPUT_SERVER, 'REQUEST_URI'),
                filter_input(INPUT_SERVER, 'REQUEST_METHOD'),
                filter_input(INPUT_SERVER, 'REDIRECT_URL'),
                filter_input_array(INPUT_GET)
            )
        );
        return $logger;
    }
}
