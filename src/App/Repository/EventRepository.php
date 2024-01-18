<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;

interface EventRepository extends Repository
{
    public function insert(Event $event): int;

    public function findByTitle(string $title): array;

    public function findAllActive(): array;

    public function findAllInactive(): array;

    public function remove(Event $event): bool;

    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array;
}
