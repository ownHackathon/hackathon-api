<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method Account offsetGet(mixed $offset)
 * @method Account current()
 * @method Account first()
 * @method Account last()
 */
interface AccountCollectionInterface extends CollectionInterface
{
}
