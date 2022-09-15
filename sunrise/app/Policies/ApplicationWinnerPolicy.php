<?php namespace App\Policies;

use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipTemplate;
use App\Entities\User;
use Doctrine\ORM\EntityManager;

/**
 * Class ApplicationWinnerPolicy
 */
class ApplicationWinnerPolicy
{
    /**
     * @param User $user
     * @param ApplicationWinner $winner
     * @return bool
     */
    public function restShow($user, $winner)
    {
        /** @var EntityManager $em */
        $em = app('em');
        $em->getFilters()->disable('soft-deleteable');

        $org = $winner->getApplication()
            ->getScholarship()
            ->getTemplate()
            ->getOrganisation();

        $em->getFilters()->enable('soft-deleteable');

        return $user->hasOrganisationRole($org->getOwnerRole());
    }

    /**
     * @param User $user
     * @param ApplicationWinner $winner
     * @return bool
     */
    public function restUpdate($user, $winner)
    {
        /** @var EntityManager $em */
        $em = app('em');
        $em->getFilters()->disable('soft-deleteable');

        $org = $winner->getApplication()
            ->getScholarship()
            ->getTemplate()
            ->getOrganisation();

        $em->getFilters()->enable('soft-deleteable');

        return $user->hasOrganisationRole($org->getOwnerRole());
    }
}
