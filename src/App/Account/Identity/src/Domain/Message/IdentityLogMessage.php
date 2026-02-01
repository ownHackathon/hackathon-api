<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain\Message;

use Exdrals\Shared\Domain\Message\LogMessage;

interface IdentityLogMessage extends LogMessage
{
    // --- 1. Account & Identity Context ---
    public const string ACCOUNT_NAME_INVALID = 'Account name is not valid';
    public const string ACCOUNT_ALREADY_EXISTS = 'An account with this email address already exists';
    public const string ACCOUNT_NOT_FOUND = 'An account with the specified email address was not found';
    public const string PASSWORD_REQUEST_MISSING_ACCOUNT = 'New password requested for a non-existent account';
    public const string PASSWORD_INCORRECT = 'Incorrect password provided';
    public const string PASSWORD_INVALID = 'Invalid password provided';
    public const string ACCOUNT_UPDATE_UNKNOWN_ERROR = 'Unknown error when updating an account';

    // --- 2. Input & Routing Errors ---
    public const string REQUIRED_EMAIL_MISSING = 'Required email argument was not provided to the route';
    public const string EMAIL_INVALID = 'Email address is not valid';
    public const string EMAIL_FORMAT_REQUIRED = 'Value must be a valid email address';

    // --- 3. System & Session Errors ---
    public const string AUTHENTICATION_PERSISTENCE_ERROR = 'Authentication could not be stored due to missing persistence data';
    public const string DUPLICATE_SOURCE_LOGIN = 'Login denied: An active session already exists from this source';
    public const string LOGIN_DENIED_AUTH_HEADER_ALREADY_PRESENT = 'Login failed: Authorization header is already present';

    // --- 4. Logout Context ---
    public const string LOGOUT_REQUIRES_AUTHENTICATION = 'Logout failed: User is not authenticated';
    public const string LOGOUT_CLIENT_IDENTITY_MISMATCH = 'Logout failed: Client identification mismatch for the account';

    // --- 5. Activation Tokens ---
    public const string ACTIVATION_TOKEN_MISSING = 'No activation token was provided';
    public const string ACTIVATION_TOKEN_INVALID = 'The provided activation token is invalid';

    // --- 6. Password Change / Reset Tokens ---
    public const string PASSWORD_CHANGE_TOKEN_MISSING = 'Password reset token was not provided';
    public const string PASSWORD_CHANGE_TOKEN_INVALID = 'Password reset token is invalid';
    public const string PASSWORD_CHANGE_TOKEN_ACCOUNT_NOT_FOUND = 'No account found for the provided password reset token';
    public const string PASSWORD_CHANGE_EMAIL_NOT_FOUND = 'No account found for the provided email';

    // --- 7. Access Tokens (Session) ---
    public const string ACCESS_TOKEN_MISSING = 'No access token provided';
    public const string ACCESS_TOKEN_EXPIRED = 'Access token has expired';
    public const string ACCESS_TOKEN_ACCOUNT_NOT_FOUND = 'Access token received, but associated account was not found';

    // --- 8. Refresh Tokens ---
    public const string REFRESH_TOKEN_MISSING = 'No refresh token provided';
    public const string REFRESH_TOKEN_INVALID = 'The provided refresh token is invalid';
    public const string REFRESH_TOKEN_EXPIRED = 'The provided refresh token is expired';
    public const string REFRESH_TOKEN_NOT_FOUND = 'The provided refresh token is unrecognized';
    public const string REFRESH_TOKEN_CLIENT_MISMATCH = 'The refresh token was issued to a different client';
    public const string REFRESH_TOKEN_ACCOUNT_NOT_FOUND = 'The account associated with the refresh token was not found';
}
