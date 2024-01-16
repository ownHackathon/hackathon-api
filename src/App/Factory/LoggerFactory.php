<?php declare(strict_types=1);

namespace App\Factory;

use App\System\Logger\BaseInformationProcessor;
use DateTime;
use Laminas\Log\Filter\Priority;
use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;
use Laminas\Log\Processor\PsrPlaceholder;
use Laminas\Log\PsrLoggerAdapter;
use Laminas\Log\Writer\Stream;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function is_dir;
use function mkdir;
use function rtrim;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        $path = $container->get('config')['logger']['path'];

        $date = (new DateTime())->format('Y-m-d');
        $path = rtrim($path, '/') . '/' . $date . '/';

        if (!is_dir($path)) {
            mkdir($path, 0775);
        }

        $formatter = new Simple(Simple::DEFAULT_FORMAT, 'Y-m-d H:i:s');

        $defaultWriter = new Stream($path . 'default.log');
        $defaultWriter->setFormatter($formatter);

        $errorWriter = new Stream($path . 'error.log');
        $errorFilter = new Priority(Logger::ERR);
        $errorWriter->addFilter($errorFilter);
        $errorWriter->setFormatter($formatter);

        $logger = new Logger();

        $logger->addWriter($defaultWriter);
        $logger->addWriter($errorWriter);

        $logger->addProcessor(new BaseInformationProcessor());
        $logger->addProcessor(new PsrPlaceholder());

        return new PsrLoggerAdapter($logger);
    }
}
