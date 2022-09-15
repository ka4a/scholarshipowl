<?php namespace App\Policies;

use App\Entities\Application;
use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\User;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Request;
use Pz\Doctrine\Rest\Exceptions\RestException;

/**
 * Application policy.
 */
class ApplicationPolicy
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ApplicationPolicy constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function restShow($user, $application)
    {
        if ($user instanceof User) {
            $this->em->getFilters()->disable('soft-deleteable');
            $organisation = $application->getScholarship()->getTemplate()->getOrganisation();
            $this->em->getFilters()->enable('soft-deleteable');
            return $user->belongsToOrganisation($organisation);
        }

        return false;
    }

    /**
     * @param User $user
     * @param Application $application
     * @return bool
     */
    public function restUpdate($user, $application)
    {
        if ($user instanceof User) {
            $this->em->getFilters()->disable('soft-deleteable');
            $organisation = $application->getScholarship()->getTemplate()->getOrganisation();
            $this->em->getFilters()->enable('soft-deleteable');
            return $user->belongsToOrganisation($organisation);
        }

        return false;
    }

    /**
     * Allow creation of scholarship only for scholarship organisation.
     *
     * @param Organisation $user
     * @return bool
     */
    public function restCreate($user)
    {
        return true;
    }

    /**
     * Verify that authenticated entity can set scholarship id of application.
     *
     * @param Authenticatable $user
     * @param Scholarship $scholarship
     * @return bool
     */
    public function applyForScholarship($user, Scholarship $scholarship)
    {
        if ($user instanceof Organisation) {
            return !empty(
                $this->em->createQueryBuilder()
                    ->select('1')
                    ->from(ScholarshipTemplate::class, 'sset')
                    ->where(':scholarship MEMBER OF sset.published AND sset.organisation = :organisation')
                    ->setParameter('scholarship', $scholarship)
                    ->setParameter('organisation', $user)
                    ->getQuery()
                    ->getArrayResult()
            );
        }

        /** @var User $user */
        if ($user instanceof User) {

            /** @var null|OrganisationRole $role */
            $role = $this->em->getRepository(OrganisationRole::class)
                ->createQueryBuilder('role')
                ->join('role.organisation', 'org')
                ->join('org.scholarships', 'sset')
                ->where(':user MEMBER OF role.users AND :scholarship MEMBER OF sset.published')
                ->setParameter('scholarship', $scholarship)
                ->setParameter('user', $user)
                ->getQuery()
                ->getOneOrNullResult();

            return $role && $role->isOwner();
        }

        return true;
    }
}
