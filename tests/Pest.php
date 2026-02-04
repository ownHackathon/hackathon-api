<?php declare(strict_types=1);

use Envms\FluentPDO\Query;
use Tests\TestIntegrationCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/
// 1. APP_ENV Logik: Priorität für GHA (action)
$appEnv = getenv('APP_ENV') ?: 'testing';
if ($appEnv !== 'action') {
    $appEnv = 'testing';
}
putenv("APP_ENV=$appEnv");
$_ENV['APP_ENV'] = $appEnv;

(function () {
    // 1. APP_ENV Logik: Priorität für GHA (action)
    $appEnv = getenv('APP_ENV') ?: 'testing';
    putenv("APP_ENV=$appEnv");
    $_ENV['APP_ENV'] = $appEnv;

    // 2. DB Host Erkennung
    $dbHost = getenv('DB_HOST') ?: (getenv('GITHUB_ACTIONS') ? '127.0.0.1' : 'database-testing');
    $dbPort = (int)(getenv('DB_PORT') ?: 3306);

    fwrite(STDOUT, "\n[Setup] Environment: $appEnv\n");
    fwrite(STDOUT, "[Setup] Waiting for database $dbHost:$dbPort...\n");

    $bin = __DIR__ . '/../bin/migrations.php';
    $maxTries = 20; // Mehr Puffer für GHA

    $connected = false;
    for ($i = 0; $i < $maxTries; $i++) {
        $fp = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if ($fp) {
            fclose($fp);
            $connected = true;
            break;
        }
        fwrite(STDOUT, '.');
        sleep(1);
    }

    if (!$connected) {
        fwrite(STDERR, "\n[Error] Database not accessible!\n");
        exit(1);
    }

    fwrite(STDOUT, "[Setup] Start migrations...\n");

    $resultCode = 0;
    passthru("php $bin migrations:migrate --no-interaction --quiet first", $resultCode);
    passthru("php $bin migrations:migrate --no-interaction --quiet", $resultCode);

    if ($resultCode !== 0) {
        exit($resultCode);
    }
    fwrite(STDOUT, "[Setup] Database ready.\n\n");
})();

uses(TestIntegrationCase::class)->beforeEach(function () {
    /** @var PDO $pdo */
    $pdo = $this->container->get(PDO::class);
    $pdo->beginTransaction();
})
    ->afterEach(function () {
        /** @var PDO $pdo */
        $pdo = $this->container->get(PDO::class);
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
    })
    ->in('Integration');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});
expect()->extend('toHaveRecord', function (array $criteria) {
    $container = test()->getContainer();

    $fluent = $container->get(Query::class);

    $result = $fluent->from($this->value)
        ->where($criteria)
        ->fetch();

    if ($result === false) {
        throw new Exception(
            sprintf(
                "Failed to find a record in table '%s' matching criteria: %s",
                $this->value,
                json_encode($criteria)
            )
        );
    }
    return $this;
});

// Optional: Das Gegenteil (dontSeeInDatabase)
expect()->extend('toNotHaveRecord', function (array $criteria) {
    $container = test()->getContainer();

    $fluent = $container->get(Query::class);

    $result = $fluent->from($this->value)->where($criteria)->fetch();

    if ($result !== false) {
        throw new Exception(
            sprintf(
                "Found unexpected record in table '%s' matching criteria: %s",
                $this->value,
                json_encode($criteria)
            )
        );
    }
    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
