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

(function () {
    putenv('APP_ENV=testing');
    $_ENV['APP_ENV'] = 'testing';

    $dbHost = getenv('DB_HOST') ?: (getenv('GITHUB_ACTIONS') ? '127.0.0.1' : 'database-testing');
    $dbPort = (int)(getenv('DB_PORT') ?: 3306);

    fwrite(STDOUT, "\n[Docker-Setup] Warte auf database-testing...\n");

    $bin = __DIR__ . '/../bin/migrations.php';
    if (!file_exists($bin)) {
        fwrite(STDERR, "[Error] Migrations-Binärdatei nicht gefunden unter: $bin\n");
        exit(1);
    }
    $maxTries = 15;
    $wait = 1; // Sekunde

    // 2. Verbindungs-Check
    $connected = false;
    for ($i = 0; $i < $maxTries; $i++) {
        $fp = @fsockopen($dbHost, $dbPort, $errno, $errstr, 2);
        if ($fp) {
            fclose($fp);
            $connected = true;
            break;
        }
        fwrite(STDOUT, '.');
        sleep($wait);
    }

    if (!$connected) {
        fwrite(STDERR, "\n[Error] Datenbank $dbHost ist nicht erreichbar!\n");
        exit(1);
    }

    fwrite(STDOUT, "\n[Docker-Setup] Starte Migrationen...\n");

    // WICHTIG: Nutze im Befehl den Pfad innerhalb des Containers
    $resultCode = 0;
    passthru("php $bin migrations:migrate --no-interaction --quiet first", $resultCode);
    passthru("php $bin migrations:migrate --no-interaction --quiet", $resultCode);

    if ($resultCode !== 0) {
        fwrite(STDERR, "[Error] Migrationen im Docker-Netzwerk fehlgeschlagen!\n");
        exit($resultCode);
    }

    fwrite(STDOUT, "[Docker-Setup] Datenbank bereit.\n\n");
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
