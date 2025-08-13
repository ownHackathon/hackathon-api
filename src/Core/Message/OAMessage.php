<?php declare(strict_types=1);

namespace ownHackathon\Core\Message;

interface OAMessage
{
    public const string SUCCESS = 'Success';

    public const string BAD_REQUEST = 'Bad Request';

    public const string UNAUTHORIZED_ACCESS = 'Unauthorized access';

    public const string FORBIDDEN = 'Access not permitted';
}
