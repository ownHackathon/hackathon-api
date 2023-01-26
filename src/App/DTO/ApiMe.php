<?php declare(strict_types=1);

namespace App\DTO;

use OpenApi\Attributes as OA;

#[OA\Schema()]
class ApiMe
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
}
