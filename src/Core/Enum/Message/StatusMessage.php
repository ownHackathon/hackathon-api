<?php declare(strict_types=1);

namespace Core\Enum\Message;

enum StatusMessage: string
{
    // --- General Responses ---
    case SUCCESS = 'Success';
    case BAD_REQUEST = 'Bad request';
    case FORBIDDEN = 'Access denied';
    case INTERNAL_SERVER_ERROR = 'Internal server error';

    // --- Validation & Input ---
    case INVALID_DATA = 'Invalid data provided';
    case EMAIL_INVALID = 'Invalid email address';
    case PASSWORD_INVALID = 'Invalid password';

    // --- Account & Client Context ---
    case ACCOUNT_ALREADY_AUTHENTICATED = 'Account is already authenticated';
    case ACCOUNT_UNAUTHORIZED = 'Unauthorized account';
    case CLIENT_UNEXPECTED = 'Unexpected client identifier';

    // --- Security & Authentication (Tokens) ---
    case UNAUTHORIZED_ACCESS = 'Unauthorized access';
    case TOKEN_INVALID = 'Invalid token';
    case TOKEN_EXPIRED = 'Token has expired';
    case TOKEN_NOT_PERSISTENT = 'Token is not persistent';
}
