<?php declare(strict_types=1);

namespace Authentication\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class MessageSchema
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
