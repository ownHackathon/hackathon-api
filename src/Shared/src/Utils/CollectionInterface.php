<?php declare(strict_types=1);

namespace Exdrals\Shared\Utils;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

interface CollectionInterface extends Countable, ArrayAccess, Iterator, JsonSerializable
{
}
