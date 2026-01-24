<?php declare(strict_types=1);

namespace Exdrals\Shared\Trait;

use DomainException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use InvalidArgumentException;
use UnexpectedValueException;

trait JwtTokenTrait
{
    public function isValid(string $token): bool
    {
        try {
            JWT::decode($token, new Key($this->config->key, $this->config->algorithmus));
        } catch (
            InvalidArgumentException
            | DomainException
            | UnexpectedValueException
            | SignatureInvalidException
            | BeforeValidException
            | ExpiredException $e
        ) {
            return false;
        }

        return true;
    }

    public function decode(string $token): object
    {
        if (!$this->isValid($token)) {
            return throw new InvalidArgumentException();
        }

        return JWT::decode($token, new Key($this->config->key, $this->config->algorithmus));
    }
}
