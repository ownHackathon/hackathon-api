<?php declare(strict_types=1);

namespace ownHackathon\Core\Message;

interface DataType
{
    public const string ARRAY = 'array';

    public const string BOOLEAN = 'boolean';

    public const string FLOAT = 'float';

    public const string INT = 'int';

    public const string NULL = 'null';

    public const string OBJECT = 'object';

    public const string RESOURCE = 'resource';

    public const string STRING = 'string';

    public const string CALLABLE = 'callable';
}
