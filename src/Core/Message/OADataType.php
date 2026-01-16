<?php declare(strict_types=1);

namespace ownHackathon\Core\Message;

interface OADataType
{
    public const string STRING = 'string';

    public const string NUMBER = 'number';

    public const string INTEGER = 'integer';

    public const string BOOLEAN = 'boolean';

    public const string ARRAY = 'array';

    public const string OBJECT = 'object';
}
