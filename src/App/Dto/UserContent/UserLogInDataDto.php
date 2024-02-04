<?php declare(strict_types=1);

namespace App\Dto\UserContent;

use OpenApi\Attributes as OA;

#[OA\Schema(required: ['username', 'password'])]
readonly class UserLogInDataDto
{
    #[OA\Property(
        description: 'The User name',
        type: 'string',
    )]
    public string $username;

    #[OA\Property(
        description: 'The Password from User',
        type: 'string',
    )]
    public string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
