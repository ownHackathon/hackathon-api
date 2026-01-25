<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Message;

interface StatusMessage
{
    public const string SUCCESS = 'Success';
    public const string BAD_REQUEST = 'Bad request';
    public const string FORBIDDEN = 'Access denied';
    public const string INTERNAL_SERVER_ERROR = 'Internal server error';
    public const string INVALID_DATA = 'Invalid data provided';
    public const string UNAUTHORIZED_ACCESS = 'Unauthorized access';
}
