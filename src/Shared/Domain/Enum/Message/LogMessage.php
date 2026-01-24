<?php declare(strict_types=1);

namespace Shared\Domain\Enum\Message;

enum LogMessage: string
{
    // --- 1. Account & Identity Context ---
    case ACCOUNT_NAME_INVALID = 'Account name is not valid';
    case ACCOUNT_ALREADY_EXISTS = 'An account with this email address already exists';
    case ACCOUNT_NOT_FOUND = 'An account with the specified email address was not found';
    case PASSWORD_REQUEST_MISSING_ACCOUNT = 'New password requested for a non-existent account';
    case PASSWORD_INCORRECT = 'Incorrect password provided';
    case PASSWORD_INVALID = 'Invalid password provided';

    // --- 2. Input & Routing Errors ---
    case REQUIRED_EMAIL_MISSING = 'Required email argument was not provided to the route';
    case EMAIL_INVALID = 'Email address is not valid';
    case EMAIL_FORMAT_REQUIRED = 'Value must be a valid email address';

    // --- 3. System & Session Errors ---
    case AUTHENTICATION_PERSISTENCE_ERROR = 'Authentication could not be stored due to missing persistence data';
    case DUPLICATE_SOURCE_LOGIN = 'Login denied: An active session already exists from this source';
    case LOGIN_DENIED_AUTH_HEADER_ALREADY_PRESENT = 'Login failed: Authorization header is already present';

    // --- 4. Logout Context ---
    case LOGOUT_REQUIRES_AUTHENTICATION = 'Logout failed: User is not authenticated';
    case LOGOUT_CLIENT_IDENTITY_MISMATCH = 'Logout failed: Client identification mismatch for the account';

    // --- 5. Activation Tokens ---
    case ACTIVATION_TOKEN_MISSING = 'No activation token was provided';
    case ACTIVATION_TOKEN_INVALID = 'The provided activation token is invalid';

    // --- 6. Password Change / Reset Tokens ---
    case PASSWORD_CHANGE_TOKEN_MISSING = 'Password reset token was not provided';
    case PASSWORD_CHANGE_TOKEN_INVALID = 'Password reset token is invalid';
    case PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND = 'No account found for the provided password reset token';

    // --- 7. Access Tokens (Session) ---
    case ACCESS_TOKEN_MISSING = 'No access token provided';
    case ACCESS_TOKEN_EXPIRED = 'Access token has expired';
    case ACCESS_TOKEN_ACCOUNT_NOT_FOUND = 'Access token received, but associated account was not found';

    // --- 8. Refresh Tokens ---
    case REFRESH_TOKEN_MISSING = 'No refresh token provided'; // (Falls du diesen Case noch ergänzt)
    case REFRESH_TOKEN_INVALID = 'The provided refresh token is invalid';
    case REFRESH_TOKEN_NOT_FOUND = 'The provided refresh token is unrecognized';
    case REFRESH_TOKEN_CLIENT_MISMATCH = 'The refresh token was issued to a different client';
    case REFRESH_TOKEN_ACCOUNT_NOT_FOUND = 'The account associated with the refresh token was not found';
}
