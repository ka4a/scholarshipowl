<?php namespace App\Policies;

use App\Entities\Organisation;
use App\Entities\Scholarship;
use App\Entities\User;
use App\Permission;
use App\Entities\ScholarshipTemplate;
use Doctrine\ORM\EntityManager;

class ScholarshipPolicy
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ScholarshipPolicy constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function restIndex($user)
    {
        if ($user instanceof User) {
            return $user->hasPermissionTo(Permission::SCHOLARSHIPS_SEARCH);
        }

        return false;
    }

    /**
     * @param $user
     * @param Scholarship|int $scholarship
     * @return bool
     */
    public function restShow($user, $scholarship)
    {
        if ($user instanceof Organisation) {
            return $user === $scholarship->getTemplate()->getOrganisation();
        }

        if ($user instanceof User) {
            return $user->belongsToOrganisation($scholarship->getTemplate()->getOrganisation());
        }

        return false;
    }

    /**
     * @param $user
     * @param ScholarshipTemplate $scholarship
     * @return bool
     */
    public function restUpdate($user, Scholarship $scholarship)
    {
        return $this->restShow($user, $scholarship);
    }

    /**
     * @param $user
     * @param ScholarshipTemplate $scholarship
     * @return bool
     */
    public function restDelete($user, Scholarship $scholarship)
    {
        return $this->restShow($user, $scholarship);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function restCreate($user)
    {
        if ($user instanceof User) {
            return $user->hasPermissionTo(Permission::SCHOLARSHIPS_CREATE);
        }

        return false;
    }

    /**
     * Is user allowed to assign different users to scholarships.
     *
     * @param User          $user
     * @param ScholarshipTemplate   $scholarship
     *
     * @return bool
     */
    public function assignUser($user, $scholarship)
    {
        if ($user instanceof User) {
            return $user->hasPermissionTo(Permission::SCHOLARSHIPS);
        }

        return false;
    }

    /**
     * @param User          $user
     * @param Scholarship   $scholarship
     * @return bool
     */
    public function republish($user, $scholarship)
    {
        if ($user instanceof User) {
            return $user->belongsToOrganisation($scholarship->getTemplate()->getOrganisation());
        }
        return false;
    }
}
