<?php namespace App\Listeners;
use App\Entities\ApplicationFile;
use App\Entities\ApplicationWinner;
use App\Entities\Scholarship;
use App\Entities\ScholarshipWinner;
use App\Events\ApplicationWinnerFormFilledEvent;
use App\Events\ScholarshipDeadlineEvent;
use App\Services\GoogleVision;
use App\Services\ScholarshipManager;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Symfony\Component\HttpFoundation\File\File;

class ScholarshipManagerSubscriber implements ShouldQueue
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * ScholarshipManagerSubscriber constructor.
     * @param EntityManager         $em
     * @param ScholarshipManager    $sm
     */
    public function __construct(EntityManager $em, ScholarshipManager $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function subscribe($dispatcher)
    {
        $dispatcher->listen(ScholarshipDeadlineEvent::class,            static::class.'@chooseWinnersOnDeadline');
        $dispatcher->listen(ScholarshipDeadlineEvent::class,            static::class.'@recurScholarshipOnDeadline');
        $dispatcher->listen(ApplicationWinnerFormFilledEvent::class,    static::class.'@saveSmallWinnerImage');
    }

    /**
     * @param ScholarshipDeadlineEvent $event
     */
    public function chooseWinnersOnDeadline(ScholarshipDeadlineEvent $event)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->em->find(Scholarship::class, $event->getScholarshipId());

        if ($scholarship->getRequirements()->isEmpty()) {
            $this->sm->chooseWinners($scholarship);
        }
    }

    /**
     * @param ScholarshipDeadlineEvent $event
     */
    public function recurScholarshipOnDeadline(ScholarshipDeadlineEvent $event)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->em->find(Scholarship::class, $event->getScholarshipId());
        $template = $scholarship->getTemplate();
        $recurrenceConfig = $template->getRecurrenceConfig();

        if ($template->isPaused()) {
            return;
        }

        if (!($recurrenceConfig && $recurrenceConfig->isRecurrable())) {
            return;
        }

        $maxOccurrences = $recurrenceConfig->getOccurrences();
        if ($maxOccurrences === 0 || $maxOccurrences > $scholarship->getOccurrence()) {
            $this->sm->recur($scholarship);
        }
    }

    /**
     * Generate small face image for the winner and save it.
     *
     * @param ApplicationWinnerFormFilledEvent $event
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveSmallWinnerImage(ApplicationWinnerFormFilledEvent $event)
    {
        /** @var ApplicationWinner $winner */
        $winner = $this->em->find(ApplicationWinner::class, $event->getApplicationWinnerId());

        $file = $winner->getPhoto()->getName();
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $extension = $extension ? sprintf('.%s', $extension) : '';
        $newFilename = sprintf('%s_small', pathinfo($file, PATHINFO_FILENAME)) . $extension;

        try {
            /** @var GoogleVision $googleVision */
            $googleVision = app(GoogleVision::class);
            $image = $googleVision->findWinnerFace($winner);
        } catch (\LogicException $e) {
            $image = \Image::make($file)->fit(ScholarshipWinner::PHOTO_SIZE);
        }

        $image->save(sprintf('%s/%s', sys_get_temp_dir(), $newFilename));

        $this->em->persist($applicationFile = ApplicationFile::uploaded(new File($image->basePath())));
        $winner->getApplication()->addFiles($applicationFile);
        $winner->setPhotoSmall($applicationFile);

        $this->em->flush();
    }
}
