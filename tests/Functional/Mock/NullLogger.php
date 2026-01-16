<?php declare(strict_types=1);

namespace Test\Functional\Mock;

use Laminas\Log\LoggerInterface;

class NullLogger implements LoggerInterface, \Psr\Log\LoggerInterface
{
    public function emerg($message, $extra = [])
    {
        // TODO: Implement emerg() method.
    }

    public function alert($message, $extra = [])
    {
        // TODO: Implement alert() method.
    }

    public function crit($message, $extra = [])
    {
        // TODO: Implement crit() method.
    }

    public function err($message, $extra = [])
    {
        // TODO: Implement err() method.
    }

    public function warn($message, $extra = [])
    {
        // TODO: Implement warn() method.
    }

    public function notice($message, $extra = [])
    {
        // TODO: Implement notice() method.
    }

    public function info($message, $extra = [])
    {
        // TODO: Implement info() method.
    }

    public function debug($message, $extra = [])
    {
        // TODO: Implement debug() method.
    }

    public function emergency($message, array $context = [])
    {
        // TODO: Implement emergency() method.
    }

    public function critical($message, array $context = [])
    {
        // TODO: Implement critical() method.
    }

    public function error($message, array $context = [])
    {
        // TODO: Implement error() method.
    }

    public function warning($message, array $context = [])
    {
        // TODO: Implement warning() method.
    }

    public function log($level, $message, array $context = [])
    {
        // TODO: Implement log() method.
    }
}
