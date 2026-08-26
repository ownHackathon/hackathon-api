<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Message;

use ownHackathon\Core\Shared\Domain\Message\StatusMessage;

interface IdentityStatusMessage extends StatusMessage
{
    public const string EMAIL_INVALID = 'Invalid email address';
    public const string PASSWORD_INVALID = 'Invalid password';

    // --- Account & Client Context ---
    public const string ACCOUNT_ALREADY_AUTHENTICATED = 'Account is already authenticated';
    public const string ACCOUNT_UNAUTHORIZED = 'Unauthorized account';
    public const string CLIENT_UNEXPECTED = 'Unexpected client identifier';

    // --- Security & Authentication (Tokens) ---
    public const string TOKEN_INVALID = 'Invalid token';
    public const string TOKEN_EXPIRED = 'Token has expired';
    public const string TOKEN_NOT_PERSISTENT = 'Token is not persistent';
}
