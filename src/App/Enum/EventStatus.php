<?php declare(strict_types=1);

namespace App\Enum;

enum EventStatus: int
{
    case SOON = 1;
    case PREPARE = 2;
    case RUNNING = 3;
    case EVALUATION = 4;
    case COMPLETE = 5;
    case CLOSED = 6;
    case ABORTED = 7;
    case HIDDEN = 8;
}
