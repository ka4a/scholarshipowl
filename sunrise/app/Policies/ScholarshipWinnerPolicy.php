<?php namespace App\Policies;

use App\Entities\ScholarshipWinner;
use App\Entities\User;

class ScholarshipWinnerPolicy
{
    /**
     * @param $user
     * @return bool
     */
    public function restIndex($user)
    {
        return true;
    }

    /**
     * @param $user
     * @return bool
     */
    public function restCreate($user)
    {
        return $user !== null;
    }

    /**
     * @param User $user
     * @param ScholarshipWinner $scholarshipWinner
     * @return bool
     */
    public function restUpdate($user, $scholarshipWinner)
    {
        $org = $scholarshipWinner
            ->getScholarship()
            ->getTemplate()
            ->getOrganisation();

        return $user->hasOrganisationRole($org->getOwnerRole());
    }

    /**
     * @return bool
     */
    public function restShow()
    {
        return true;
    }
}
