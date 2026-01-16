<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use ownHackathon\App\Entity\AccountAccessAuth;
use ownHackathon\Core\Utils\CollectionInterface;

/**
 * @method AccountAccessAuth offsetGet(mixed $offset)
 * @method AccountAccessAuth current()
 * @method AccountAccessAuth first()
 * @method AccountAccessAuth last()
 */
interface AccountAccessAuthCollectionInterface extends CollectionInterface
{
}
