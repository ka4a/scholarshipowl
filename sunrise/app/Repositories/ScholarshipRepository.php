<?php namespace App\Repositories;

use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\User;
use App\Services\ApplicationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\RestRepository;

/**
 * Class ScholarshipRepository
 * @method Scholarship findById($id)
 */
class ScholarshipRepository extends RestRepository
{
    /**
     * Apply filter for only published scholarships.
     *
     * @param QueryBuilder $qb
     * @param string $scholarship
     * @param string $template
     * @return QueryBuilder
     */
    static public function applyPublishedScholarships(QueryBuilder $qb, $scholarship = 's', $template = 't')
    {
        return $qb
            ->join("$scholarship.template", $template)
            ->andWhere("$scholarship.expiredAt IS NULL")
            ->andWhere("$template.deletedAt IS NULL");
    }

    /**
     * Find scholarship that belongs to the User.
     *
     * @param User $user
     * @return array
     */
    public function findByUser(User $user)
    {
        $query = static::applyPublishedScholarships($this->createQueryBuilder('s'))
            ->select('s.id')
            ->join('t.organisation', 'o')
            ->join('o.roles', 'orr', 'WITH', 'orr.owner = TRUE')
            ->join('orr.users', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user)
            ->getQuery();

        if ($user->isRoot()) {
            $query = $this->queryAllPublished();
        }

        return array_map('current', $query->getScalarResult());
    }

    /**
     * @param ScholarshipTemplate $template
     * @return Scholarship
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findSinglePublishedByTemplate(ScholarshipTemplate $template)
    {
        return $this->createQueryBuilder('s')
            ->where('s.template = :template AND s.expiredAt IS NULL')
            ->setParameter('template', $template)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return array|Scholarship[]
     */
    public function findAllPublished()
    {
        return $this->queryAllPublished()->getResult();
    }

    /**
     * @param array $data
     * @param $query
     * @return Query
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function queryEligible(array $data, $query)
    {
        /** @var ApplicationService $service */
        $service = app(ApplicationService::class);
        return $this->createQueryBuilder('s')
            ->where('s.id IN (:ids)')
            ->setParameter('ids', $service->eligible($data, $query))
            ->getQuery();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function queryAllPublished()
    {
        return static::applyPublishedScholarships($this->createQueryBuilder('s'))->getQuery();
    }
}
