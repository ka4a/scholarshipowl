<?php namespace App\Transformers;

use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\User;
use App\Entities\UserTutorial;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    const INCLUDE_ROLES = 'roles';

    const INCLUDE_ORGANISATIONS = 'organisations';

    const INCLUDE_TUTORIALS = 'tutorials';

    protected $availableIncludes = [
        self::INCLUDE_ROLES,
        self::INCLUDE_ORGANISATIONS,
        self::INCLUDE_TUTORIALS,
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'            => $user->getId(),
            'name'          => $user->getName(),
            'email'         => $user->getEmail(),
            'picture'       => $user->getPicture(),
        ];
    }

    /**
     * @param User $user
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $user)
    {
        return $this->collection($user->getRoles(), new RoleTransformer(), 'role');
    }

    /**
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeOrganisations(User $user)
    {
        return $this->collection(
            $user->getOrganisationRoles()->map(function(OrganisationRole $role) {
                return $role->getOrganisation();
            }),
            new OrganisationTransformer(),
            Organisation::getResourceKey()
        );
    }

    /**
     * @param User $user
     * @return \League\Fractal\Resource\Item
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function includeTutorials(User $user)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        /** @var UserTutorial $tutorial */
        if (null === ($tutorial = $em->getRepository(UserTutorial::class)->find($user))) {
            $tutorial = new UserTutorial();
            $tutorial->setUser($user);
            $em->persist($tutorial);
            $em->flush($tutorial);
        }

        return $this->item($tutorial, new UserTutorialTransformer(), $tutorial->getResourceKey());
    }
}
