<?php declare(strict_types=1);

namespace App\Token\Api;

use App\Token\Api\DTO\PasswordChangeTokenDto;

interface PasswordChangeTokenServiceInterface
{
    public function insert(PasswordChangeTokenDto $passwordChangeTokenDto): void;

    public function findOneByToken(string $token): PasswordChangeTokenDto;

    public function deleteById(int $id): void;
}
