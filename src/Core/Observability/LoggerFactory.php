<?php declare(strict_types=1);

namespace ownHackathon\Core\Observability;

use DateTime;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function in_array;
use function is_dir;
use function mkdir;
use function rtrim;

readonly final class LoggerFactory
{
    public const string DEFAULT_CHANNEL = 'log';
    public const string FORMAT_JSON = 'json';

    public function build(ContainerInterface $container, string $channel): LoggerInterface
    {
        return $this->buildLogger($container, $channel);
    }

    private function buildLogger(ContainerInterface $container, string $channel): LoggerInterface
    {
        /** @var array{logger: array{path: string, format?: string, channels?: array<string, array{file?: string, format?: string}>}} $config */
        $config = $container->get('config');
        $path = $config['logger']['path'];
        $format = $config['logger']['format'] ?? '';
        $channelConfig = $config['logger']['channels'][$channel] ?? [];

        $date = (new DateTime())->format('Y-m-d');
        $path = rtrim($path, '/') . '/' . $date . '/';

        if (!is_dir($path)) {
            mkdir($path, 0775);
        }

        $formatter = $this->createFormatter($channelConfig['format'] ?? $format);

        $logger = new Logger($channel);

        $file = $channelConfig['file'] ?? null;
        if ($file !== null) {
            $logger->pushHandler(new StreamHandler($path . $file)->setFormatter($formatter));
            $logger->pushProcessor(new PsrLogMessageProcessor());
            $logger->pushProcessor(
                new MetaDataProcessor(
                    filter_input(INPUT_SERVER, 'REMOTE_ADDR'),
                    filter_input(INPUT_SERVER, 'REQUEST_URI'),
                    filter_input(INPUT_SERVER, 'REQUEST_METHOD'),
                    filter_input(INPUT_SERVER, 'REDIRECT_URL'),
                    filter_input_array(INPUT_GET),
                ),
            );
            return $logger;
        }

        $stackTraceFormater = clone $formatter;
        if ($stackTraceFormater instanceof LineFormatter) {
            $stackTraceFormater->includeStacktraces(true);
        }

        $logger->pushHandler(new StreamHandler($path . 'default.log')->setFormatter($formatter));

        $errorHandler = new StreamHandler($path . 'error.log', Level::Error)->setFormatter($formatter);
        $errorHandler = new FilterHandler($errorHandler, Level::Error, Level::Error);

        $logger->pushHandler($errorHandler);

        $warningHandler = new StreamHandler($path . 'warning.log', Level::Warning)->setFormatter($formatter);
        $warningHandler = new FilterHandler($warningHandler, Level::Warning, Level::Error);

        $logger->pushHandler($warningHandler);

        $logger->pushHandler(
            new StreamHandler($path . 'critical.log', Level::Critical)->setFormatter($stackTraceFormater),
        );
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $logger->pushProcessor(
            new MetaDataProcessor(
                filter_input(INPUT_SERVER, 'REMOTE_ADDR'),
                filter_input(INPUT_SERVER, 'REQUEST_URI'),
                filter_input(INPUT_SERVER, 'REQUEST_METHOD'),
                filter_input(INPUT_SERVER, 'REDIRECT_URL'),
                filter_input_array(INPUT_GET),
            ),
        );
        return $logger;
    }

    private function createFormatter(string $format): FormatterInterface
    {
        if (in_array($format, [self::FORMAT_JSON], true)) {
            return new JsonFormatter();
        }

        $dateFormat = 'Y-m-d H:i:s';
        $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        return new LineFormatter($output, $dateFormat);
    }

    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        return $this->buildLogger($container, self::DEFAULT_CHANNEL);
    }
}
