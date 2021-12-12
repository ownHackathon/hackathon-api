<?php declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Participant;
use App\Model\User;
use Laminas\View\Helper\AbstractHelper;

class IsParticipant extends AbstractHelper
{
    /**
     * @param null|Participant[] $participants
     */
    public function __invoke(?User $user, ?array $participants): bool
    {
        if (!$user instanceof User) {
            return false;
        }

        if (empty($participants)) {
            return false;
        }

        foreach ($participants as $participant) {
            if ($user->getId() === $participant[User::class]->getId()) {
                return true;
            }
        }

        return false;
    }
}
