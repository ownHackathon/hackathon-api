<?php declare(strict_types=1);

namespace App\Dto\Core;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class HttpStatusCodeMessage
{
    #[OA\Property(
        description: 'The HTTP Status Code',
        type: 'integer'
    )]
    public int $status;

    #[OA\Property(
        description: 'The Error Message',
        type: 'string'
    )]
    public string $message;

    #[OA\Property(
        description: 'Further explanatory notes',
        type: 'array',
        items: new OA\Items()
    )]
    public array $data;

    public function __construct(int $status, string $message, array $data = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }
}
