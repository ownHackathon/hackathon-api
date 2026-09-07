<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Api\DTO\AuthenticatedAccountDto;
use App\Account\Identity\Domain\Account;
use App\Account\Identity\Domain\AccountInterface;
use Psr\Http\Message\ServerRequestInterface;

readonly final class AccountResolver
{
    public function resolveFromRequest(ServerRequestInterface $request): ?AccountInterface
    {
        $accountDto = $request->getAttribute(AuthenticatedAccountDto::class);

        return $accountDto instanceof AuthenticatedAccountDto ? new Account(
            $accountDto->id,
            $accountDto->uuid,
            $accountDto->name,
            $accountDto->hashedPassword,
            $accountDto->email,
            $accountDto->registeredAt,
            $accountDto->lastActionAt,
        ) : null;
    }
}
