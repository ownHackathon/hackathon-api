<?php declare(strict_types=1);

namespace Tests\Unit;

use Laminas\Diactoros\ServerRequest;
use ownHackathon\App\Http\Exception\HttpDuplicateEntryException;
use ownHackathon\App\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\App\Http\Exception\HttpUnauthorizedException;
use ownHackathon\App\Http\Handler\PingHandler;
use ownHackathon\App\Http\Handler\SwaggerUIHandler;
use ownHackathon\App\Http\Factory\ErrorResponseFactory;
use ownHackathon\App\Http\Middleware\RouteNotFoundMiddleware;
use Psr\Log\LoggerInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function expect;
use function json_decode;
use function test;

test('HTTP exceptions expose status context and message', function (): void {
    $exception = new HttpDuplicateEntryException('log', 'response', ['field' => 'name']);
    expect($exception->getCode())->toBe(409)
        ->and($exception->getContext())->toBe(['field' => 'name'])
        ->and($exception->getResponseMessage())->toBe('response')
        ->and((new HttpInvalidArgumentException('a', 'b'))->getHttpStatusCode())->toBe(400)
        ->and((new HttpUnauthorizedException('a', 'b'))->getHttpStatusCode())->toBe(401);
});

test('ping handler returns runtime information', function (): void {
    $response = (new PingHandler())->handle(new ServerRequest());
    $body = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expect($response->getStatusCode())->toBe(200)
        ->and($body['message'])->toBe('pong')
        ->and($body['php_version'])->toBe(PHP_VERSION)
        ->and($body['ack'])->toBeInt();
});

test('error response factory maps handled and unhandled exceptions', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->exactly(2))->method('log');
    $factory = new ErrorResponseFactory($logger);

    $handled = $factory->createFromThrowable(new \ownHackathon\App\Http\Exception\HttpInvalidArgumentException('log', 'bad'));
    $unhandled = $factory->createFromThrowable(new \RuntimeException('failure'));
    expect($handled->getStatusCode())->toBe(400)->and($unhandled->getStatusCode())->toBe(500);
});

test('route-not-found middleware logs and delegates', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('notice')->with('Route not found');
    $response = new \Laminas\Diactoros\Response\EmptyResponse();
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);
    expect((new RouteNotFoundMiddleware($logger))->process(new ServerRequest(), $handler))->toBe($response);
});

test('swagger handler serves the documentation page', function (): void {
    if (!defined('ROOT_DIR')) {
        define('ROOT_DIR', dirname(__DIR__, 2) . '/');
    }
    $response = (new SwaggerUIHandler())->handle(new ServerRequest());
    expect($response->getStatusCode())->toBe(200)->and($response->getHeaderLine('content-type'))->toContain('text/html');
});
