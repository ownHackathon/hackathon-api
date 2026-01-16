<?php declare(strict_types=1);

namespace Core\Entity\Account;

use App\Entity\Account\Account;
use Core\Utils\CollectionInterface;

/**
 * @method Account offsetGet(mixed $offset)
 * @method Account current()
 * @method Account first()
 * @method Account last()
 */
interface AccountCollectionInterface extends CollectionInterface
{
}
