<?php declare(strict_types=1);

namespace Tests\Unit\Core\Http;

use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Monolog\Level;
use ownHackathon\Core\Http\DTO\HttpResponseMessage;
use ownHackathon\Core\Http\Exception\HttpDuplicateEntryException;
use ownHackathon\Core\Http\Exception\HttpHandledInvalidArgumentAsSuccessException;
use ownHackathon\Core\Http\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Http\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Http\Factory\ErrorResponseFactory;
use ownHackathon\Core\Http\Handler\PingHandler;
use ownHackathon\Core\Http\Handler\SwaggerUIHandler;
use ownHackathon\Core\Http\Middleware\PaginationMiddleware;
use ownHackathon\Core\Http\Middleware\RouteNotFoundMiddleware;
use ownHackathon\Core\Persistence\Pagination;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use function expect;
use function json_decode;
use function test;

test('HTTP exceptions expose status context and message', function (): void {
    $exception = new HttpDuplicateEntryException('log', 'response', ['field' => 'name']);
    expect($exception->getCode())->toBe(Http::STATUS_CONFLICT)
        ->and($exception->getContext())->toBe(['field' => 'name'])
        ->and($exception->getResponseMessage())->toBe('response')
        ->and((new HttpInvalidArgumentException('a', 'b'))->getHttpStatusCode())->toBe(Http::STATUS_BAD_REQUEST)
        ->and((new HttpUnauthorizedException('a', 'b'))->getHttpStatusCode())->toBe(Http::STATUS_UNAUTHORIZED);
});

test('duplicate entry exception preserves all constructor data and uses conflict status', function (): void {
    $previous = new \RuntimeException('database constraint');
    $exception = new HttpDuplicateEntryException(
        'Duplicate workspace',
        'workspace name already in use',
        ['Workspace:' => 'existing-workspace'],
        Level::Error,
        $previous,
    );

    expect($exception->getMessage())->toBe('Duplicate workspace')
        ->and($exception->getCode())->toBe(Http::STATUS_CONFLICT)
        ->and($exception->getHttpStatusCode())->toBe(Http::STATUS_CONFLICT)
        ->and($exception->getResponseMessage())->toBe('workspace name already in use')
        ->and($exception->getContext())->toBe(['Workspace:' => 'existing-workspace'])
        ->and($exception->getLogLevel())->toBe(Level::Error)
        ->and($exception->getPrevious())->toBe($previous);
});

test('duplicate entry exception applies warning log level and empty context by default', function (): void {
    $exception = new HttpDuplicateEntryException('duplicate log', 'duplicate response');

    expect($exception->getContext())->toBe([])
        ->and($exception->getLogLevel())->toBe(Level::Warning)
        ->and($exception->getPrevious())->toBeNull();
});

test('handled invalid argument exception exposes a successful response status', function (): void {
    $previous = new \InvalidArgumentException('invalid input was handled');
    $exception = new HttpHandledInvalidArgumentAsSuccessException(
        'Invalid account state',
        'account already activated',
        ['account' => 'user@example.test'],
        Level::Info,
        $previous,
    );

    expect($exception->getMessage())->toBe('Invalid account state')
        ->and($exception->getCode())->toBe(Http::STATUS_OK)
        ->and($exception->getHttpStatusCode())->toBe(Http::STATUS_OK)
        ->and($exception->getResponseMessage())->toBe('account already activated')
        ->and($exception->getContext())->toBe(['account' => 'user@example.test'])
        ->and($exception->getLogLevel())->toBe(Level::Info)
        ->and($exception->getPrevious())->toBe($previous);
});

test('handled invalid argument exception uses notice log level by default', function (): void {
    $exception = new HttpHandledInvalidArgumentAsSuccessException('handled log', 'handled response');

    expect($exception->getLogLevel())->toBe(Level::Notice)
        ->and($exception->getContext())->toBe([])
        ->and($exception->getPrevious())->toBeNull();
});

test('HTTP response message maps its values', function (): void {
    expect(HttpResponseMessage::create(201, 'created'))->toEqual(new HttpResponseMessage(201, 'created'));
});

test('ping handler returns runtime information', function (): void {
    $response = (new PingHandler())->handle(new ServerRequest());
    $body = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    expect($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($body['message'])->toBe('pong')
        ->and($body['php_version'])->toBe(PHP_VERSION)
        ->and($body['ack'])->toBeInt();
});

test('error response factory maps handled and unhandled exceptions', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->exactly(2))->method('log');
    $factory = new ErrorResponseFactory($logger);

    $handled = $factory->createFromThrowable(new HttpInvalidArgumentException('log', 'bad'));
    $unhandled = $factory->createFromThrowable(new \RuntimeException('failure'));
    expect($handled->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)->and($unhandled->getStatusCode())->toBe(Http::STATUS_INTERNAL_SERVER_ERROR);
});

test('route-not-found middleware logs and delegates', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('notice')->with('Route not found');
    $response = new \Laminas\Diactoros\Response\EmptyResponse();
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);
    expect((new RouteNotFoundMiddleware($logger))->process(new ServerRequest(), $handler))->toBe($response);
});

test('pagination middleware attaches pagination to the request', function (): void {
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->with(
        $this->callback(static fn ($request): bool => $request->getAttribute(Pagination::class) instanceof Pagination)
    );
    (new PaginationMiddleware())->process((new ServerRequest())->withQueryParams(['page' => '2', 'limit' => '10']), $handler);
});

test('swagger handler serves the documentation page', function (): void {
    $indexFile = dirname(__DIR__, 4) . '/public/api/docs/index.html';
    $response = (new SwaggerUIHandler($indexFile))->handle(new ServerRequest());
    $body = (string) $response->getBody();

    expect($response)->toBeInstanceOf(HtmlResponse::class)
        ->and($response->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($response->getHeaderLine('content-type'))->toContain('text/html')
        ->and($body)->toContain('<title>ownHackathon - SwaggerUI</title>')
        ->and($body)->toContain('<div id="swagger-ui"></div>')
        ->and($body)->toContain("url: 'swagger.json'")
        ->and($body)->toContain('SwaggerUIBundle');
});

test('swagger handler returns the same documentation for any request method', function (): void {
    $indexFile = dirname(__DIR__, 4) . '/public/api/docs/index.html';

    $handler = new SwaggerUIHandler($indexFile);
    $getResponse = $handler->handle((new ServerRequest())->withMethod('GET'));
    $headResponse = $handler->handle((new ServerRequest())->withMethod('HEAD'));

    expect((string) $getResponse->getBody())->toBe((string) $headResponse->getBody())
        ->and($headResponse->getStatusCode())->toBe(Http::STATUS_OK)
        ->and($headResponse->getHeaderLine('content-type'))->toContain('text/html');
});

test('swagger handler returns no content when the documentation file is missing', function (): void {
    $response = (new SwaggerUIHandler('/does/not/exist/index.html'))->handle(new ServerRequest());
    expect($response->getStatusCode())->toBe(Http::STATUS_NO_CONTENT);
});