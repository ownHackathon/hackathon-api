<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;

interface EventRepository extends Repository
{
    public function insert(Event $event): int|bool;

    public function findByTitle(string $topic): bool|array;

    public function findAllActive(): bool|array;

    public function findAllNotActive(): bool|array;

    public function remove(Event $event): bool|int;

    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array;
}
