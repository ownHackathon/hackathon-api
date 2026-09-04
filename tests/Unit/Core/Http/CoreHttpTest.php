<?php declare(strict_types=1);

namespace Tests\Unit\Core\Http;

use Core\Http\DTO\HttpResponseMessage;
use Core\Http\Exception\HttpDuplicateEntryException;
use Core\Http\Exception\HttpHandledInvalidArgumentAsSuccessException;
use Core\Http\Exception\HttpInvalidArgumentException;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\Http\Factory\ErrorResponseFactory;
use Core\Http\Factory\SwaggerUIHandlerFactory;
use Core\Http\Handler\PingHandler;
use Core\Http\Handler\SwaggerUIHandler;
use Core\Http\Middleware\PaginationMiddleware;
use Core\Http\Middleware\RequestCorrelationMiddleware;
use Core\Http\Middleware\RequestLoggingMiddleware;
use Core\Http\Middleware\RouteNotFoundMiddleware;
use Core\Observability\CorrelationIdRegistry;
use Core\Persistence\Pagination;
use Core\SharedKernel\Utils\UuidFactory;
use Fig\Http\Message\StatusCodeInterface as Http;
use InvalidArgumentException;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequest;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

use RuntimeException;

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
    $previous = new RuntimeException('database constraint');
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
    $previous = new InvalidArgumentException('invalid input was handled');
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
    $unhandled = $factory->createFromThrowable(new RuntimeException('failure'));
    expect($handled->getStatusCode())->toBe(Http::STATUS_BAD_REQUEST)->and($unhandled->getStatusCode())->toBe(Http::STATUS_INTERNAL_SERVER_ERROR);
});

test('error response factory strips clear-text contact fields from the log context', function (): void {
    $loggedContext = null;
    $logger = $this->createMock(LoggerInterface::class);
    $logger->method('log')->willReturnCallback(
        static function ($level, $message, array $context) use (&$loggedContext): void {
            $loggedContext = $context;
        },
    );
    $factory = new ErrorResponseFactory($logger);

    $factory->createFromThrowable(new HttpInvalidArgumentException(
        'dup',
        'bad',
        [
            'E-Mail:' => 'max@example.org',
            'Account' => 'Max Mustermann',
            'accountUuid' => '11111111-1111-1111-1111-111111111111',
            'emailHash' => 'hashed',
        ],
    ));

    expect($loggedContext)->not->toHaveKey('E-Mail:')
        ->and($loggedContext)->not->toHaveKey('Account')
        ->and($loggedContext)->toHaveKey('accountUuid')
        ->and($loggedContext)->toHaveKey('emailHash');
});

test('route-not-found middleware logs and delegates', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('notice')->with('Route not found');
    $response = new EmptyResponse();
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);
    expect((new RouteNotFoundMiddleware($logger))->process(new ServerRequest(), $handler))->toBe($response);
});

test('request correlation middleware sets and clears the correlation id', function (): void {
    $uuidFactory = new UuidFactory();
    $response = new EmptyResponse();
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->with(
        $this->callback(static fn ($request): bool => $request->getAttribute(RequestCorrelationMiddleware::CORRELATION_ID) !== null),
    )->willReturn($response);
    $middleware = new RequestCorrelationMiddleware($uuidFactory);

    CorrelationIdRegistry::clear();
    $result = $middleware->process(new ServerRequest(), $handler);

    expect($result)->toBe($response)
        ->and(CorrelationIdRegistry::has())->toBeFalse();
});

test('request correlation middleware uses an incoming correlation id header', function (): void {
    $uuidFactory = new UuidFactory();
    $response = new EmptyResponse();
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->with(
        $this->callback(static fn ($request): bool => $request->getAttribute(RequestCorrelationMiddleware::CORRELATION_ID) === 'trace-123'),
    )->willReturn($response);
    $middleware = new RequestCorrelationMiddleware($uuidFactory);

    CorrelationIdRegistry::clear();
    $middleware->process((new ServerRequest())->withHeader(RequestCorrelationMiddleware::HEADER, ' trace-123 '), $handler);
});

test('request logging middleware logs request details on success', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('log')->with(
        Level::Info->value,
        $this->callback(static fn ($message): bool => str_contains($message, 'GET')),
        $this->callback(static fn (array $context): bool => ($context['method'] ?? '') === 'GET'),
    );
    $response = new EmptyResponse(Http::STATUS_OK);
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);

    expect((new RequestLoggingMiddleware($logger))->process(new ServerRequest(), $handler))->toBe($response);
});

test('request logging middleware warns on client errors', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('log')->with(
        Level::Warning->value,
        $this->anything(),
        $this->callback(static fn (array $context): bool => ($context['status'] ?? 0) === Http::STATUS_NOT_FOUND),
    );
    $response = new EmptyResponse(Http::STATUS_NOT_FOUND);
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);

    (new RequestLoggingMiddleware($logger))->process(new ServerRequest(), $handler);
});

test('request logging middleware logs errors on server errors', function (): void {
    $logger = $this->createMock(LoggerInterface::class);
    $logger->expects($this->once())->method('log')->with(
        Level::Error->value,
        $this->anything(),
        $this->callback(static fn (array $context): bool => ($context['status'] ?? 0) === Http::STATUS_INTERNAL_SERVER_ERROR),
    );
    $response = new EmptyResponse(Http::STATUS_INTERNAL_SERVER_ERROR);
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->willReturn($response);

    (new RequestLoggingMiddleware($logger))->process(new ServerRequest(), $handler);
});

test('pagination middleware attaches pagination to the request', function (): void {
    $handler = $this->createMock(RequestHandlerInterface::class);
    $handler->expects($this->once())->method('handle')->with(
        $this->callback(static fn ($request): bool => $request->getAttribute(Pagination::class) instanceof Pagination),
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

test('swagger UI handler factory creates handler with config index_file', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $container->method('get')->willReturnCallback(
        static function ($service): mixed {
            if ($service === 'config') {
                return [
                    'swagger_ui' => [
                        'index_file' => '/some/path/index.html',
                    ],
                ];
            }
            throw new InvalidArgumentException("Unknown service: $service");
        },
    );

    $factory = new SwaggerUIHandlerFactory();
    $handler = $factory($container);

    expect($handler)->toBeInstanceOf(SwaggerUIHandler::class)
        ->and($handler instanceof SwaggerUIHandler);
});

test('swagger UI handler factory creates handler with default empty index_file', function (): void {
    $container = $this->createMock(ContainerInterface::class);
    $container->method('get')->willReturnCallback(
        static function ($service): mixed {
            if ($service === 'config') {
                return [
                    'swagger_ui' => [],
                ];
            }
            throw new InvalidArgumentException("Unknown service: $service");
        },
    );

    $factory = new SwaggerUIHandlerFactory();
    $handler = $factory($container);

    expect($handler)->toBeInstanceOf(SwaggerUIHandler::class);
});
