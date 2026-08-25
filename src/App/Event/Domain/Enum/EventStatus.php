<?php declare(strict_types=1);

namespace ownHackathon\Event\Domain\Enum;

enum EventStatus: int
{
    case DRAFT = 1;
    case SOON = 2;
    case REGISTRATION = 3;
    case RUNNING = 4;
    case EVALUATION = 5;
    case FINISHED = 6;
    case ABORTED = 7;
    case CLOSED = 8;

    public function getEventStatusName(): string
    {
        return match ($this) {
            EventStatus::DRAFT => 'Draft',
            EventStatus::SOON => 'Soon',
            EventStatus::REGISTRATION => 'Registration',
            EventStatus::RUNNING => 'Running',
            EventStatus::EVALUATION => 'Evaluation',
            EventStatus::FINISHED => 'Finished',
            EventStatus::ABORTED => 'Aborted',
            EventStatus::CLOSED => 'Closed',
        };
    }
}
