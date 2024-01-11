<?php declare(strict_types=1);

namespace App\Dto\Core;

use App\Entity\User;
use App\Enum\UserRole;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class ApiMeDto
{
    #[OA\Property(
        description: 'unique one-time identification number of the user',
        type: 'string',
        example: 'bec152fa163d406696633263761cbfbd'
    )]
    public string $uuid;

    #[OA\Property(
        description: 'the name of the user\'s account',
        type: 'string',
        example: 'ExampleUserName'
    )]
    public string $name;

    #[OA\Property(
        description: 'the authorization role from the user',
        type: 'string',
        example: 'User'
    )]
    public string $role;

    public function __construct(User $user)
    {
        $this->uuid = $user->getUuid();
        $this->name = $user->getName();
        $this->role = UserRole::from($user->getRoleId())->getRoleName();
    }
}
