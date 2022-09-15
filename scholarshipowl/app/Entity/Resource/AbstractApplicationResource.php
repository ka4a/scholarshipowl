<?php namespace App\Entity\Resource;

use App\Entity\Account;
use App\Entity\AccountsFavoriteScholarships;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Http\Controllers\Rest\ScholarshipRestController;
use App\Services\ScholarshipService;
use App\Services\SuperCollege\SuperCollegeService;
use ScholarshipOwl\Data\AbstractResource;

abstract class AbstractApplicationResource extends AbstractResource
{
    /**
     * @var ApplicationRequirementContract
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $fullScholarship = true;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * ApplicationTextResource constructor.
     *
     * @param bool $fullScholarship
     */
    public function __construct(bool $fullScholarship = true)
    {
        $this->setFullScholarship($fullScholarship);
        $this->scholarships = \EntityManager::getRepository(Scholarship::class);
    }

    /**
     * @param $fullScholarship
     *
     * @return $this
     */
    public function setFullScholarship($fullScholarship)
    {
        $this->fullScholarship = $fullScholarship;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFullScholarship()
    {
        return $this->fullScholarship;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    protected function applyScholarship(array $array)
    {
        $id = $this->entity->getScholarship()->getScholarshipId();

        if ($this->isFullScholarship()) {
            /**
             * @var ScholarshipService $scholarshipService
             */
            $scholarshipService = app(ScholarshipService::class);

            $account = $this->entity->getAccount();
            $scholarship = $this->scholarships->findWithAccountApplications([$id], $account);
            /**
             * @var Scholarship $scholarship
             */
            $scholarship = array_shift($scholarship);

            $favorites = $scholarshipService->getFavoritesScholarship($account);
            if(in_array($id, $favorites) && $scholarship instanceof Scholarship){
                $scholarship->setFavorite();
            }

            $scholarship->nl2br();
            $resource = new ScholarshipResource($account);
            $resource->setEntity($scholarship);


            $array['scholarship'] = $resource->toArray();
        } else {
            $array['scholarshipId'] = $id;
        }

        return $array;
    }

    /**
     * Retrun list of  favorite users scholarship
     * @param Account $account
     *
     * @return array
     */
    protected function getFavoritesScholarships(Account $account){

        $favoriteRepo = \EntityManager::getRepository(AccountsFavoriteScholarships::class);
        $favorites = $favoriteRepo->findBy(['accountId' => $account->getAccountId(),'favorite' => self::FAVORITE_STATUS]);
        $favoriteScholarshipIds = [];
        foreach ($favorites as $favorite){
            $favoriteScholarshipIds[] = $favorite->getScholarshipId()->getScholarshipId();
        }

        return $favoriteScholarshipIds;
    }
}
