<?php namespace App\Transformers;

use App\Entities\Country;
use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\User;
use App\Traits\HasEntityManager;
use Illuminate\Support\Facades\Gate;
use League\Fractal\TransformerAbstract;

class OrganisationTransformer extends TransformerAbstract
{
    use HasEntityManager;

    /**
     * @var array
     */
    protected $availableIncludes = [
        'published',
        'scholarships',
        'owners',
        'roles',
        'state',
        'country',
    ];

    /**
     * @param Organisation $organisation
     * @return array
     */
    public function transform(Organisation $organisation)
    {
        return [
            'id' => $organisation->getId(),
            // 'apiKey' => $organisation->getApiToken(),

            'name' => $organisation->getName(),
            'businessName' => $organisation->getBusinessName(),
            'city' => $organisation->getCity(),
            'address' => $organisation->getAddress(),
            'address2' => $organisation->getAddress2(),
            'zip' => $organisation->getZip(),

            'website' => $organisation->getWebsite(),
            'phone' => $organisation->getPhone(),
            'email' => $organisation->getEmail(),

        ];
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Item
     */
    public function includeState(Organisation $organisation)
    {
        if (is_null($organisation->getState())) {
            return $this->null();
        }

        return $this->item(
            $organisation->getState(),
            new StateTransformer(),
            $organisation->getState()->getResourceKey()
        );
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(Organisation $organisation)
    {
        return $this->collection(
            $organisation->getRoles(),
            new OrganisationRoleTransformer(),
            OrganisationRole::getResourceKey()
        );
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Collection
     */
    public function includePublished(Organisation $organisation)
    {
        $published = $this->em()->getRepository(Scholarship::class)
            ->createQueryBuilder('s')
            ->leftJoin('s.template', 't')
            ->where('t.organisation = :organisation')
            ->setParameter('organisation', $organisation)
            ->getQuery()
            ->getResult();

        return $this->collection(
            $published,
            new ScholarshipTransformer(),
            Scholarship::getResourceKey()
        );
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Collection
     */
    public function includeScholarships(Organisation $organisation)
    {
        return $this->collection(
            $organisation->getScholarships(),
            new ScholarshipTemplateTransformer(),
            ScholarshipTemplate::getResourceKey()
        );
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Collection
     */
    public function includeOwners(Organisation $organisation)
    {
        Gate::authorize('showOwners', $organisation);
        return $this->collection(
            $organisation->getOwnerRole()->getUsers(),
            new UserTransformer(),
            User::getResourceKey()
        );
    }

    /**
     * @param Organisation $organisation
     * @return \League\Fractal\Resource\Item
     */
    public function includeCountry(Organisation $organisation)
    {
        return $this->item($organisation->getCountry(), new CountryTransformer(), Country::getResourceKey());
    }
}
