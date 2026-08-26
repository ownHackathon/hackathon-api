<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

interface AccountCreatorInterface
{
    public function create(AccountInterface $account): AccountInterface;
}
