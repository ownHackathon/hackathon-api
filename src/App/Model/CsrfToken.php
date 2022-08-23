<?php declare(strict_types=1);

namespace App\Model;

class CsrfToken
{
    private string $csrfToken = '';

    public function getCsrfToken(): string
    {
        return $this->csrfToken;
    }

    public function setCsrfToken(string $csrfToken): self
    {
        $this->csrfToken = $csrfToken;

        return $this;
    }
}
