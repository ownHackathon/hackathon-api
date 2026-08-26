<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use ownHackathon\Core\Shared\Utils\CollectionInterface;

/**
 * @method Account offsetGet(mixed $offset)
 * @method Account current()
 * @method Account first()
 * @method Account last()
 */
interface AccountCollectionInterface extends CollectionInterface
{
}
