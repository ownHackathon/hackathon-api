<?php declare(strict_types=1);

(function () {
    // 1. APP_ENV Logik: Priorität für GHA (action)
    $appEnv = getenv('APP_ENV') ?: 'testing';
    putenv("APP_ENV=$appEnv");
    $_ENV['APP_ENV'] = $appEnv;

    // 2. DB Host Erkennung
    $dbHost = getenv('DB_HOST') ?: (getenv('GITHUB_ACTIONS') ? '127.0.0.1' : 'database-testing');
    $dbPort = (int)(getenv('DB_PORT') ?: 3306);

    fwrite(STDOUT, "\n[Setup-Neu] Nutze Umgebung: $appEnv\n");
    fwrite(STDOUT, "[Setup-Neu] Warte auf Datenbank $dbHost:$dbPort...\n");

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
        fwrite(STDERR, "\n[Error] Datenbank nicht erreichbar!\n");
        exit(1);
    }

    fwrite(STDOUT, "\n[Setup-Neu] Migrationen starten...\n");

    $resultCode = 0;
    passthru("php $bin migrations:migrate --no-interaction --quiet first", $resultCode);
    passthru("php $bin migrations:migrate --no-interaction --quiet", $resultCode);

    if ($resultCode !== 0) {
        exit($resultCode);
    }
    fwrite(STDOUT, "[Setup-Neu] Datenbank bereit.\n\n");
})();
