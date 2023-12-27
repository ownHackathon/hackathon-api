<?php declare(strict_types=1);

namespace App\Dto;

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

    public function __construct(int $status, string $message)
    {
        $this->status = $status;
        $this->message = $message;
    }
}
