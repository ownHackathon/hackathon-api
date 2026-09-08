<?php declare(strict_types=1);

namespace App\Account\Identity\Api;

use Psr\Http\Server\MiddlewareInterface;

interface RequireAuthenticatedAccountMiddlewareInterface extends MiddlewareInterface
{
}
