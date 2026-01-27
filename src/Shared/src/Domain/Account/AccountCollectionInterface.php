<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Account;

use Exdrals\Identity\Domain\Account;
use Exdrals\Shared\Utils\CollectionInterface;

/**
 * @method Account offsetGet(mixed $offset)
 * @method Account current()
 * @method Account first()
 * @method Account last()
 */
interface AccountCollectionInterface extends CollectionInterface
{
}
