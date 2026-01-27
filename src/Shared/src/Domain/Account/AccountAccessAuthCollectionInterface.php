<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Account;

use Exdrals\Identity\Domain\AccountAccessAuth;
use Exdrals\Shared\Utils\CollectionInterface;

/**
 * @method AccountAccessAuth offsetGet(mixed $offset)
 * @method AccountAccessAuth current()
 * @method AccountAccessAuth first()
 * @method AccountAccessAuth last()
 */
interface AccountAccessAuthCollectionInterface extends CollectionInterface
{
}
