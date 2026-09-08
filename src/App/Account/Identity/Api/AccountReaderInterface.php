<?php declare(strict_types=1);

namespace App\Account\Identity\Api;

use Core\SharedKernel\Domain\Exception\EmptyResultException;

interface AccountReaderInterface
{
    /**
     * Loads the public profile of the account with the given identifier.
     *
     * @throws EmptyResultException when no account with the given id exists
     */
    public function findOneById(int $id): AccountProfileInterface;
}
