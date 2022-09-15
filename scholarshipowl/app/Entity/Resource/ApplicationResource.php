<?php namespace App\Entity\Resource;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\ApplicationEssay;
use App\Entity\Essay;
use App\Entity\EssayFiles;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use ScholarshipOwl\Data\AbstractResource;
use ScholarshipOwl\Data\ResourceCollection;

class ApplicationResource extends AbstractResource
{
    /**
     * @var Application
     */
    protected $entity;

    /**
     * @var bool Include application data
     */
    protected $withApplicationData = false;

    public function setWithApplicationData(bool $val)
    {
        $this->withApplicationData = $val;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $scholarshipResource =  new ScholarshipResource();
        $scholarshipId = $this->entity->getScholarship()->getScholarshipId();

        if ($this->withApplicationData) {
            /** @var ScholarshipRepository $sr */
            $sr = \EntityManager::getRepository(Scholarship::class);

            $account = \Auth::user();
            $scholarshipResource->setAccount(\Auth::user());
            $scholarship = $sr->withAccountApplications([$scholarshipId], $account)
                ->getQuery()
                ->getResult()[0];
        } else {
            $scholarship = $this->entity->getScholarship();
        }

        $scholarshipResource->setEntity($scholarship);

        return [
            'scholarshipId' => $scholarshipId,
            'scholarship'   => $scholarshipResource->toArray(),
            'dateApplied'   => $this->entity->getDateApplied(),
            'accountId'     => $this->entity->getAccount()->getAccountId(),
            'status'        => $this->entity->getApplicationStatus()->getName(),
            'externalStatusUpdatedAt' => $this->entity->getExternalStatusUpdatedAt()
        ];
    }
}
