<?php declare(strict_types=1);

namespace App\Account\Identity\Api;

use Ramsey\Uuid\UuidInterface;

interface AccountProfileInterface
{
    public const string AUTHENTICATED = 'account.authenticated.class';

    public ?int $id { get; }

    public UuidInterface $uuid { get; }

    public string $name { get; }
}
