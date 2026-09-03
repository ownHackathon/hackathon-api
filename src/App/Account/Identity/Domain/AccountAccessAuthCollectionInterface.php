<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use Core\SharedKernel\Utils\CollectionInterface;

/**
 * @method AccountAccessAuth offsetGet(mixed $offset)
 * @method AccountAccessAuth current()
 * @method AccountAccessAuth first()
 * @method AccountAccessAuth last()
 */
interface AccountAccessAuthCollectionInterface extends CollectionInterface
{
}
