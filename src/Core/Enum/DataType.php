<?php declare(strict_types=1);

namespace ownHackathon\Core\Enum;

enum DataType: string
{
    case ARRAY = 'array';
    case BOOLEAN = 'boolean';
    case FLOAT = 'float';
    case INTEGER = 'integer';
    case NULL = 'null';
    case OBJECT = 'object';
    case RESOURCE = 'resource';
    case STRING = 'string';
    case CALLABLE = 'callable';

    /** only for open api */
    public const string NUMBER = 'number';
}
