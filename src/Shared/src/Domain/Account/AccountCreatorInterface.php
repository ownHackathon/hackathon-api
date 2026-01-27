<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Account;

interface AccountCreatorInterface
{
    public function create(AccountInterface $account): AccountInterface;
}
