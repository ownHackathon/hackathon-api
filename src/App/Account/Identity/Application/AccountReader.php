<?php declare(strict_types=1);

namespace App\Account\Identity\Application;

use App\Account\Identity\Api\AccountProfileInterface;
use App\Account\Identity\Api\AccountReaderInterface;
use App\Account\Identity\Api\DTO\Account\AccountProfile;
use App\Account\Identity\Domain\Repository\AccountRepositoryInterface;

readonly final class AccountReader implements AccountReaderInterface
{
    public function __construct(
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    #[\Override]
    public function findOneById(int $id): AccountProfileInterface
    {
        $account = $this->accountRepository->findOneById($id);

        return new AccountProfile(
            id: $account->id,
            uuid: $account->uuid,
            name: $account->name,
        );
    }
}
