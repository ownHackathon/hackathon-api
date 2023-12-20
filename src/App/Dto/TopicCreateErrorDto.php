<?php declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class TopicCreateErrorDto
{
    #[OA\Property(
        properties: [
            new OA\Property(property: 'topic', type: 'string'),
        ],
        type: 'object'
    )]
    public array $topic;
}
