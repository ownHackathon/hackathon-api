<?php declare(strict_types=1);

namespace App\Dto\UserContent;

use OpenApi\Attributes as OA;

#[OA\Schema(required: ['username', 'password'])]
class UserLogInDataDto
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
}
