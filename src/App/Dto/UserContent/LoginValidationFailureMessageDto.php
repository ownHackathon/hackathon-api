<?php declare(strict_types=1);

namespace App\Dto\UserContent;

use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class LoginValidationFailureMessageDto
{
    #[OA\Property(
        description: 'Error description',
        type: 'string'
    )]
    public string $message;

    #[OA\Property(
        description: 'Validation failure Message',
        type: 'string',
        example: 'string|null'
    )]
    public ?string $username;

    #[OA\Property(
        description: 'Validation failure Message',
        type: 'string',
        example: 'string|null'
    )]
    public ?string $password;

    public function __construct(string $message, array $data)
    {
        $this->message = $message;
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? null;
    }
}
