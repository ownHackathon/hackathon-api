<?php declare(strict_types=1);

namespace App\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class TopicCreateErrorSchema
{
    #[OA\Property(
        properties: [
            new OA\Property(property: 'topic', type: 'string'),
        ],
        type: 'object'
    )]
    public array $topic;
}
