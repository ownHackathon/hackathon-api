<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use ownHackathon\App\Entity\AccountActivation;
use ownHackathon\Core\Utils\CollectionInterface;

/**
 * @method AccountActivation offsetGet(mixed $offset)
 * @method AccountActivation current()
 * @method AccountActivation first()
 * @method AccountActivation last()
 */
interface AccountActivationCollectionInterface extends CollectionInterface
{
}
