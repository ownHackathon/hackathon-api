<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;

interface EventRepository extends Repository
{
    public function insert(Event $event): int|bool;

    public function findByTitle(string $title): bool|array;

    public function findAllActive(): bool|array;

    public function findAllInactive(): bool|array;

    public function remove(Event $event): bool|int;

    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array;
}
