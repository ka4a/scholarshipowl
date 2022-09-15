<?php namespace Test\Entity\Repository;

use App\Entity\ApplicationStatus;
use App\Entity\EligibilityCache;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Events\Account\ApplicationsAddEvent;
use App\Events\Account\ApplicationsRemoveEvent;
use App\Services\EligibilityCacheService;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;

class ScholarshipRepositoryTest extends TestCase
{
    use EntityGenerator;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    public function setUp(): void
    {
        parent::setUp();

        $this->elbCacheService = app(EligibilityCacheService::class);
    }

    /**
     * @return ScholarshipRepository
     */
    protected function repository()
    {
        return \EntityManager::getRepository(Scholarship::class);
    }

    public function testFindReadyToSubmitScholarships()
    {
        static::$truncate = ['eligibility_cache', 'scholarship', 'application'];

        $scholarship = $this->generateScholarship(ScholarshipStatus::UNPUBLISHED);
        $account = $this->generateAccount();
        \EntityManager::flush();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);

        $ids = [$scholarship->getScholarshipId()];
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        // empty because has no subscription and unpublished
        $this->assertEmpty($scholarships);

        \EntityManager::flush($scholarship->setStatus(ScholarshipStatus::PUBLISHED));
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        // empty because has no subscription
        $this->assertEmpty($scholarships);

        $this->generateSubscription(null, $account);

        $account->flushCacheTag();
        $this->elbCacheService->updateAccountEligibilityCache($account->getAccountId(), true);
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));

        $account->flushCacheTag();
        \EntityManager::flush($scholarship->setExpirationDate(new \DateTime('-1 day')));
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertEmpty($scholarships);

        $scholarship->setExpirationDate(new \DateTime('+1 day'));
        $application = $this->generateApplication($scholarship, $account);
        \EntityManager::flush();
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));

        \EntityManager::flush($application->setApplicationStatus(ApplicationStatus::SUCCESS));
        \Event::dispatch(new ApplicationsAddEvent($account)); // needed to clear eligibility cache
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertEmpty($scholarships);

        \EntityManager::remove($application);
        \EntityManager::flush();
        \Event::dispatch(new ApplicationsRemoveEvent($account)); // needed to clear eligibility cache
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));

        $account->flushCacheTag();
        $requirementText = $this->generateRequirementText($scholarship);
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertEmpty($scholarships);

        \EntityManager::remove($requirementText);
        \EntityManager::flush();
        $account->flushCacheTag();
        $requirementFile = $this->generateRequirementFile($scholarship);
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertEmpty($scholarships);

        \EntityManager::remove($requirementFile);
        \EntityManager::flush();
        $account->flushCacheTag();
        $requirementImage = $this->generateRequirementImage($scholarship);
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertEmpty($scholarships);
        \EntityManager::remove($requirementImage);
        \EntityManager::flush();

        $scholarship2 = $this->generateScholarship();
        array_push($ids, $scholarship2->getScholarshipId());
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));
        $this->assertTrue($scholarships->contains($scholarship2));

        $requirementText2 = $this->generateRequirementText($scholarship2);
        $requirementFile2 = $this->generateRequirementFile($scholarship2);
        $requirementImage2 = $this->generateRequirementImage($scholarship2);
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));
        $this->assertFalse($scholarships->contains($scholarship2));

        $this->generateApplicationText($requirementText2, null, 'test', $account);
        $this->generateApplicationFile($this->generateAccountFile($account), $requirementFile2);
        $this->generateApplicationImage($this->generateAccountFile($account), $requirementImage2);
        $account->flushCacheTag();
        $scholarships = $this->repository()->findReadyToSubmitScholarships($account, $ids);
        $this->assertTrue($scholarships->contains($scholarship));
        $this->assertTrue($scholarships->contains($scholarship2));
    }

    public function testFindAutomaticScholarships()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();
        $scholarship2->setIsAutomatic(true);
        \EntityManager::flush($scholarship2);
        /** @var ArrayCollection $r */
        $r = $this->repository()->findAutomaticScholarships($account);
        $this->assertTrue($r->current()->getScholarshipId() === $scholarship2->getScholarshipId());
    }

    public function testFindEligibleScholarshipsIds()
    {
        $account = $this->generateAccount('t@t.com');
        $scholarship = $this->generateScholarship();
        $scholarship2 = $this->generateScholarship();

        $ids = $this->repository()->findEligibleNotAppliedScholarshipsIds($account);
        $this->assertTrue(count($ids) === 2);
        $this->assertTrue(in_array($scholarship->getScholarshipId(), $ids) && in_array($scholarship2->getScholarshipId(), $ids));
    }
}
