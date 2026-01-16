<?php declare(strict_types=1);

namespace Core\Entity\Account;

use App\Entity\Account\AccountActivation;
use Core\Utils\CollectionInterface;

/**
 * @method AccountActivation offsetGet(mixed $offset)
 * @method AccountActivation current()
 * @method AccountActivation first()
 * @method AccountActivation last()
 */
interface AccountActivationCollectionInterface extends CollectionInterface
{
}
