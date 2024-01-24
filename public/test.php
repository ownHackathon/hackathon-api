<?php declare(strict_types=1);

use Ramsey\Uuid\Uuid;

require './vendor/autoload.php';

$dateTime = new DateTimeImmutable('2022-04-10 23:24:10');
$uuid = Uuid::uuid7($dateTime);

printf(
    "UUID: %s\nVersion: %d\nDate: %s\n",
    $uuid->toString(),
    $uuid->getFields()->getVersion(),
    $uuid->getDateTime()->format('r'),
);
