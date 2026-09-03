<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

interface AccountCreatorInterface
{
    public function create(AccountInterface $account): AccountInterface;
}
