<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use ownHackathon\App\Entity\Account\Account;
use ownHackathon\Core\Utils\CollectionInterface;

/**
 * @method Account offsetGet(mixed $offset)
 * @method Account current()
 * @method Account first()
 * @method Account last()
 */
interface AccountCollectionInterface extends CollectionInterface
{
}
