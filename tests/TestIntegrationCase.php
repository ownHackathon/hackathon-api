<?php declare(strict_types=1);

namespace Tests;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Uri;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use PDO;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Tests\Integration\App\Account\Identity\Factory\AccountFactory;
use Tests\Integration\JsonFactory;

use function array_merge;

abstract class TestIntegrationCase extends BaseTestCase
{
    protected ContainerInterface $container;
    protected Application $app;

    protected function setUp(): void
    {
        parent::setUp();
        putenv('APP_ENV=testing');
        $_ENV['APP_ENV'] = 'testing';
        $this->container = require __DIR__ . '/../config/container.php';

        $this->app = $this->container->get(Application::class);
        $factory = $this->container->get(MiddlewareFactory::class);

        (require __DIR__ . '/../config/pipeline.php')($this->app, $factory, $this->container);

        (require __DIR__ . '/../config/routes.php')($this->app, $factory, $this->container);
    }

    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $path = __DIR__ . '/../config/container.php';
            if (!file_exists($path)) {
                throw new RuntimeException("Container-Konfiguration nicht gefunden unter: $path");
            }
            $this->container = require $path;
        }

        return $this->container;
    }

    public function createGetRequest(string $path): ServerRequest
    {
        return (new ServerRequestFactory())
            ->createServerRequest('GET', new Uri($path));
    }

    public function createJsonPostRequest(string $path, array $data): ServerRequest
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', new Uri($path))
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($data));
        $request->getBody()->rewind();

        return $request;
    }

    public function createJsonPatchRequest(string $path, array $data): ServerRequest
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest('PATCH', new Uri($path))
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($data));
        $request->getBody()->rewind();

        return $request;
    }

    public function insert(string $table, array $data): void
    {
        /** @var PDO $pdo */

        $pdo = test()->container->get(PDO::class);

        $keys = array_keys($data);
        $cols = implode(', ', $keys);
        $placeholders = implode(', ', array_fill(0, count($keys), '?'));

        $sql = "INSERT INTO $table ($cols) VALUES ($placeholders)";
        $pdo->prepare($sql)->execute(array_values($data));
    }

    public function createAndLoginUser(): array
    {
        $account = AccountFactory::create();

        $request = $this->createJsonPostRequest(
            '/api/account/authentication',
            [
                'email' => $account['email'],
                'password' => $account['password'],
            ]
        );
        $response = $this->app->handle($request);

        $responseDate = JsonFactory::create($response);

        return array_merge($account, $responseDate);
    }
}
