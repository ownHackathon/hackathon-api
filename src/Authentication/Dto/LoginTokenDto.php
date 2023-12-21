<?php declare(strict_types=1);

namespace Authentication\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class LoginTokenDto
{
    #[OA\Property(
        description: 'The token after a valid log-in',
        type: 'string',
    )]
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
