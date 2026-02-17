<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Persistence\Contract;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
