<?php declare(strict_types=1);

namespace App\DTO\Account;

use OpenApi\Attributes as OA;
use Core\Enum\DataType;

#[OA\Schema(required: ['email', 'password'])]
readonly class AccountAuthenticationData
{
    #[OA\Property(
        description: 'The E-Mail from Account',
        type: DataType::STRING->value,
    )]
    public string $email;

    #[OA\Property(
        description: 'The Password from Account',
        type: DataType::STRING->value,
    )]
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
