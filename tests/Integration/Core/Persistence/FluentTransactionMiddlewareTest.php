<?php declare(strict_types=1);

namespace Tests\Integration\Core\Persistence;

use Envms\FluentPDO\Query;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\EmptyResponse;
use ownHackathon\Core\Persistence\Middleware\FluentTransactionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

use function expect;
use function test;

function middlewareHandler(?RuntimeException $exception = null): RequestHandlerInterface
{
    return new class ($exception) implements RequestHandlerInterface {
        public function __construct(private readonly ?RuntimeException $exception)
        {
        }

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            if ($this->exception !== null) {
                throw $this->exception;
            }

            return new EmptyResponse();
        }
    };
}

test('transaction middleware skips read requests and nested transactions', function () {
    $pdo = $this->container->get(Query::class)->getPdo();
    $middleware = new FluentTransactionMiddleware($this->container->get(Query::class));

    $factory = new ServerRequestFactory();
    $middleware->process($factory->createServerRequest('GET', 'http://localhost'), middlewareHandler());
    expect($pdo->inTransaction())->toBeTrue();

    $middleware->process($factory->createServerRequest('POST', 'http://localhost'), middlewareHandler());
    expect($pdo->inTransaction())->toBeTrue();
});

test('transaction middleware commits successful writes and rolls back failures', function () {
    $pdo = $this->container->get(Query::class)->getPdo();
    $pdo->rollBack();
    $middleware = new FluentTransactionMiddleware($this->container->get(Query::class));

    $factory = new ServerRequestFactory();
    $response = $middleware->process(
        $factory->createServerRequest('POST', 'http://localhost'),
        middlewareHandler()
    );
    expect($response)->toBeInstanceOf(ResponseInterface::class)
        ->and($pdo->inTransaction())->toBeFalse();

    expect(fn() => $middleware->process(
        $factory->createServerRequest('POST', 'http://localhost'),
        middlewareHandler(new RuntimeException('handler failed'))
    ))->toThrow(RuntimeException::class, 'handler failed');
    expect($pdo->inTransaction())->toBeFalse();
});
