<?php declare(strict_types=1);

namespace Authentication\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class LoginTokenSchema
{
    #[OA\Property(
        description: 'The token after a valid registration. Without login equals null',
        type: 'string',
        nullable: true,
    )]
    public ?string $token;

    public function __construct(?string $token)
    {
        $this->token = $token;
    }
}
