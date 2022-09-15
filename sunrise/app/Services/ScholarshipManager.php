<?php namespace App\Services;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Entities\ScholarshipRequirement;
use App\Entities\ScholarshipTemplate;
use App\Entities\ApplicationWinner;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipTemplateRequirement;
use App\Events\ApplicationAwardedEvent;
use App\Events\ScholarshipDeadlineEvent;
use App\Events\ScholarshipPublishedEvent;
use App\Events\ScholarshipRecurredEvent;
use App\Events\ScholarshipStatusChangedEvent;
use App\Events\ScholarshipUnpublishedEvent;
use App\Repositories\ApplicationRepository;
use App\Repositories\ApplicationWinnersRepository;
use App\Repositories\ScholarshipRepository;
use App\Services\ApplicationService\ApplicationServiceException;
use App\Services\ScholarshipManager\ContentManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class ScholarshipManager
{
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContentManager
     */
    protected $contentManager;

    /**
     * ScholarshipManager constructor.
     * @param EntityManager $em
     * @param ContentManager $contentManager
     */
    public function __construct(EntityManager $em, ContentManager $contentManager)
    {
        $this->em = $em;
        $this->contentManager = $contentManager;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|ScholarshipRepository
     */
    public function published()
    {
        return $this->em->getRepository(Scholarship::class);
    }

    /**
     * Should run each 10 minutes to deactivate scholarships.
     *
     * @param \DateTime|null $date
     * @param array $ids Specify specific scholarship IDs for testing.
     * @throws \Exception
     */
    public function maintain(\DateTime $date = null, $ids = [])
    {
        $date = $date ?: new \DateTime();

        $qb = ScholarshipRepository::applyPublishedScholarships($this->published()->createQueryBuilder('s'));

        if (!empty($ids)) {
            $qb->andWhere('s.id IN (:ids)')->setParameter('ids', $ids);
        }

        /** @var Scholarship[] $scholarships */
        foreach (QueryIterator::create($qb->getQuery()) as $scholarships) {
            foreach ($scholarships as $scholarship) {
                $timezone = $scholarship->getTimezoneObj();
                $format = static::DEFAULT_FORMAT;

                $now = clone $date;
                $now = new \DateTimeImmutable($now->setTimezone($timezone)->format($format), $timezone);
                $start = new \DateTimeImmutable($scholarship->getStart()->format($format), $timezone);
                $deadline = new \DateTimeImmutable($scholarship->getDeadline()->format($format), $timezone);

                if ($now >= $start && $scholarship->getStatus() === Scholarship::STATUS_UNPUBLISHED) {
                    $this->updateStatus($scholarship, Scholarship::STATUS_PUBLISHED);
                }

                if ($now >= $deadline && $scholarship->getStatus() === Scholarship::STATUS_PUBLISHED) {
                    $this->expire($scholarship);
                }
            }
        }
    }

    /**
     * @param Scholarship $scholarship
     * @param null|int $awards You can pick number of awards to pick.
     */
    /**
     * @param Scholarship $scholarship
     * @param null $awards
     * @return ArrayCollection
     * @throws \Doctrine\ORM\ORMException
     */
    public function chooseWinners(Scholarship $scholarship, $awards = null)
    {
        $result = new ArrayCollection();

        /** @var ApplicationWinnersRepository $applicationWinnersRepo */
        $applicationWinnersRepo = $this->em->getRepository(ApplicationWinner::class);

        /** @var ApplicationRepository $applicationsRepo */
        $applicationsRepo = $this->em->getRepository(Application::class);

        if ($applicationWinnersRepo->countWinners($scholarship) >= $scholarship->getAwards()) {
            throw new \LogicException('Can\'t select another winner as scholarship already have enough winners.');
        }

        if ($applicationsRepo->countUnreviewed($scholarship)) {
            throw new \LogicException('Scholarship have unreviewed applications.');
        }

        $accepted = $applicationsRepo->findAccepted($scholarship);

        if (empty($accepted)) {
            return $result;
        }

        $numberOfWinners = is_null($awards) ? $scholarship->getAwards() : $awards;
        $winners = array_random($accepted, min(count($accepted), $numberOfWinners));

        /** Memory usage can be big for many applications */
        unset($accepted);

        foreach ($winners as $winnerApplicationId) {

            /** @var Application $application */
            if ($application = $this->em->find(Application::class, $winnerApplicationId)) {
                $winner = new ApplicationWinner();
                $winner->setName($application->getName());
                $winner->setEmail($application->getEmail());
                $winner->setPhone($application->getPhone());
                $winner->setState($application->getState());
                $winner->setApplication($application);

                $this->em->persist($winner);
                $this->em->flush($winner);

                ApplicationAwardedEvent::dispatch($winner);

                $result->add($winner);
            }

        }

        return $result;
    }

    /**
     * @param Scholarship $scholarship
     * @param string $status
     * @return ScholarshipManager
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateStatus(Scholarship $scholarship, $status): self
    {
        $this->em->flush($scholarship->setStatus($status));

        ScholarshipStatusChangedEvent::dispatch($scholarship);

        return $this;
    }

    /**
     * @param Scholarship $scholarship
     * @param \DateTime|null $expireData
     * @return $this
     * @throws \Exception
     */
    public function expire(Scholarship $scholarship, \DateTime $expireData = null)
    {
        $expireData = $expireData ?: new \DateTime();
        $this->em->flush($scholarship->setStatus(Scholarship::STATUS_EXPIRED)->setExpiredAt($expireData));

        ScholarshipDeadlineEvent::dispatch($scholarship);

        return $this;
    }

    /**
     * @param ScholarshipTemplate $template
     * @param Scholarship|null $previous
     * @return Scholarship
     * @throws \Exception
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws ApplicationServiceException
     */
    public function publish(ScholarshipTemplate $template, Scholarship $previous = null)
    {
        if (!$template->getRecurrenceConfig()) {
            throw new \RuntimeException('Template recurrence config is not set');
        }

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em->getRepository(Scholarship::class);
        if ($scholarshipRepository->findSinglePublishedByTemplate($template)) {
            throw new \RuntimeException('Scholarship already published!');
        }

        try {
             $this->em->beginTransaction();

            /** @var Scholarship $published */
            $published = $this->generateScholarship($template, $previous);
            $this->em->persist($published);
            $this->em->flush($published);

            $content = $this->contentManager->generateScholarshipContent($published);
            $published->setContent($content);
            $this->em->persist($content);
            $this->em->flush($content);

            $this->em->commit();

        } catch (\Exception $e) {
            $this->em->rollback();
            throw new ApplicationServiceException('Unhandelable error on scholarship publish.', 0, $e);
        }

        ScholarshipPublishedEvent::dispatch($published);

        return $published;
    }

    /**
     * Unpublish scholarship without running recurrence mechanism.
     *
     * @param Scholarship $scholarship
     * @param \DateTime|null $unpublishDate
     * @throws \Exception
     */
    public function unpublish(Scholarship $scholarship, \DateTime $unpublishDate = null)
    {
        $unpublishDate = $unpublishDate ?: new \DateTime();
        $this->em->flush($scholarship->setExpiredAt($unpublishDate));

        $this->updateStatus($scholarship, Scholarship::STATUS_UNPUBLISHED);

        ScholarshipUnpublishedEvent::dispatch($scholarship);
    }

    /**
     * Rebuild scholarship content.
     *
     * @param Scholarship $published
     * @return Scholarship
     * @throws \Exception
     */
    public function republish(Scholarship $published)
    {
        $this->contentManager->generateScholarshipContent($published, $published->getContent());

        $published = $this->setupScholarshipBasics($published, $published->getTemplate());
        $published = $this->setupScholarshipFields($published, $published->getTemplate());
        $published = $this->setupScholarshipRequirements($published, $published->getTemplate());

        $this->em->flush();

        ScholarshipPublishedEvent::dispatch($published);

        return $published;
    }

    /**
     * @param Scholarship $previous
     * @return Scholarship
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function recur(Scholarship $previous)
    {
        if (!$previous->getTemplate()->getRecurrenceConfig()->isRecurrable()) {
            throw new \InvalidArgumentException('Scholarship template should be recurring!');
        }

        $scholarship = $this->publish($previous->getTemplate(), $previous);

        ScholarshipRecurredEvent::dispatch($scholarship);

        return $scholarship;
    }

    /**
     * Prepare scholarship instance to be published.
     *
     * @param ScholarshipTemplate   $template
     * @param Scholarship           $previous
     * @return Scholarship
     * @throws \Exception
     */
    private function generateScholarship(ScholarshipTemplate $template, Scholarship $previous = null)
    {
        $scholarship = $this->setupScholarshipBasics(new Scholarship(), $template);
        $scholarship = $this->setupScholarshipFields($scholarship, $template);
        $scholarship = $this->setupScholarshipRequirements($scholarship, $template);
        $scholarship = $this->setupScholarshipDeadline($scholarship, $template, $previous);

        $template->addPublished($scholarship);

        return $scholarship;
    }

    /**
     * @param Scholarship $scholarship
     * @param ScholarshipTemplate $template
     * @return Scholarship
     */
    private function setupScholarshipBasics(Scholarship $scholarship, ScholarshipTemplate $template)
    {
        $scholarship->setTitle($template->getTitle());
        $scholarship->setDescription($template->getDescription());
        $scholarship->setTimezone($template->getTimezone());
        $scholarship->setAwards($template->getAwards());
        $scholarship->setAmount($template->getAmount());
        $scholarship->setIsFree($template->isIsFree());
        $scholarship->setScholarshipUrl($template->getScholarshipUrl());
        $scholarship->setScholarshipPPUrl($template->getScholarshipPPUrl());
        $scholarship->setScholarshipTOSUrl($template->getScholarshipTOSUrl());
        return $scholarship;
    }

    /**
     * @param Scholarship $scholarship
     * @param ScholarshipTemplate $template
     * @return Scholarship
     */
    private function setupScholarshipFields(Scholarship $scholarship, ScholarshipTemplate $template)
    {
        $scholarship->setFields(
            $template->getFields()->map(
                function (ScholarshipTemplateField $templateField) use ($scholarship) {
                    $field = new ScholarshipField();
                    $field->setField($templateField->getField());
                    $field->setEligibilityType($templateField->getEligibilityType());
                    $field->setEligibilityValue($templateField->getEligibilityValue());
                    $field->setOptional($templateField->isOptional());
                    $field->setScholarship($scholarship);
                    return $field;
                }
            )
        );

        return $scholarship;
    }

    /**
     * @param Scholarship $scholarship
     * @param ScholarshipTemplate $template
     * @return Scholarship
     */
    private function setupScholarshipRequirements(Scholarship $scholarship, ScholarshipTemplate $template)
    {
        $scholarship->setRequirements(
            $template->getRequirements()->map(
                function(ScholarshipTemplateRequirement $requirement) use ($scholarship) {
                    $scholarshipRequirement = new ScholarshipRequirement();
                    $scholarshipRequirement->setRequirement($requirement->getRequirement());
                    $scholarshipRequirement->setConfig($requirement->getConfig());
                    $scholarshipRequirement->setTitle($requirement->getTitle());
                    $scholarshipRequirement->setDescription($requirement->getDescription());
                    $scholarshipRequirement->setScholarship($scholarship);
                    return $scholarshipRequirement;
                }
            )
        );

        return $scholarship;
    }

    /**
     * @param Scholarship $scholarship
     * @param ScholarshipTemplate $template
     * @param Scholarship|null $previous
     * @return Scholarship
     * @throws \Exception
     */
    private function setupScholarshipDeadline(
        Scholarship $scholarship,
        ScholarshipTemplate $template,
        Scholarship $previous = null
    ) {
        $timezone = $scholarship->getTimezoneObj();
        $recurrenceConfig = $template->getRecurrenceConfig();
        $scholarship->setRecurringType($recurrenceConfig->getRecurringType());
        $scholarship->setRecurringValue($recurrenceConfig->getRecurringValue());

        if (is_null($previous)) {

            /**
             * First time scholarship creation
             */
            $scholarship->setStart($recurrenceConfig->getStartDate());
            $scholarship->setDeadline($recurrenceConfig->getDeadlineDate());

            if ($recurrenceConfig->isRecurrable()) {
                $scholarship->setOccurrence(1);
            }

        } else {

            /**
             * Recurring scholarship creation
             */
            $scholarship->setStart($recurrenceConfig->getStartDate($previous->getStart(), 2));
            $scholarship->setDeadline($recurrenceConfig->getDeadlineDate($previous->getDeadline(), 2));

            if ($recurrenceConfig->isRecurrable()) {
                $scholarship->setOccurrence($previous->getOccurrence() + 1);
            }

        }

        $now = new \DateTimeImmutable(
            (new \DateTime('now', $timezone))->format(static::DEFAULT_FORMAT),
            $timezone
        );

        $start = new \DateTimeImmutable(
            $scholarship->getStart()->format(static::DEFAULT_FORMAT),
            $timezone
        );

        $scholarship->setStatus($start > $now ? Scholarship::STATUS_UNPUBLISHED : Scholarship::STATUS_PUBLISHED);

        return $scholarship;
    }
}
