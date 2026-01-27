<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Account;

use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Shared\Utils\CollectionInterface;

/**
 * @method AccountActivation offsetGet(mixed $offset)
 * @method AccountActivation current()
 * @method AccountActivation first()
 * @method AccountActivation last()
 */
interface AccountActivationCollectionInterface extends CollectionInterface
{
}
