<?php declare(strict_types=1);

namespace Core\Persistence\Transaction;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
