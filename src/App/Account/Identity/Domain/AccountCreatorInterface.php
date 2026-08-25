<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

interface AccountCreatorInterface
{
    public function create(AccountInterface $account): AccountInterface;
}
