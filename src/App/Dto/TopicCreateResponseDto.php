<?php declare(strict_types=1);

namespace App\Dto;

use App\Entity\Topic;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class TopicCreateResponseDto extends TopicCreateRequestDto
{
    #[OA\Property(
        description: 'unique one-time identification number of the topic',
        type: 'string',
    )]
    public string $uuid;

    public function __construct(Topic $topic)
    {
        $this->uuid = $topic->getUuid();
        parent::__construct($topic);
    }
}
