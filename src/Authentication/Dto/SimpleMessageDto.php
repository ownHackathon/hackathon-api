<?php declare(strict_types=1);

namespace Authentication\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class SimpleMessageDto
{
    #[OA\Property(
        description: 'A message to the API user',
        type: 'string'
    )]
    public string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
