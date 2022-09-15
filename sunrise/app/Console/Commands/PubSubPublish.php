<?php

namespace App\Console\Commands;

use App\Entities\Application;
use App\Entities\Scholarship;
use App\Repositories\ScholarshipRepository;
use App\Services\PubSubService;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;
use Pz\Doctrine\Rest\RestRepository;

class PubSubPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubsub:publish
        {message            : Pubsub event name}
        {--scholarshipId=   : Scholarship id for event.}
        {--applicationId=   : Application id for event.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "PubSub publish test.\n\n"
        ."\tScholarship topic '".PubSubService::TOPIC_SCHOLARSHIP."':\n\n"
        ."\t\t" . PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED . "\n"
        ."\t\t" . PubSubService::MESSAGE_SCHOLARSHIP_DEADLINE . "\n"
        ."\n\n"
        ."\tScholarship topic '".PubSubService::TOPIC_APPLICATION."':\n\n"
        ."\t\t" . PubSubService::MESSAGE_APPLICATION_APPLIED . "\n"
        ."\t\t" . PubSubService::MESSAGE_APPLICATION_WINNER . "\n"
        ."\t\t" . PubSubService::MESSAGE_APPLICATION_WINNER_FILLED . "\n"
        ."\t\t" . PubSubService::MESSAGE_APPLICATION_WINNER_DISQUALIFIED . "\n"
        ."\t\t" . PubSubService::MESSAGE_APPLICATION_WINNER_PUBLISHED . "\n";

    /**
     * @var PubSubService
     */
    protected $pubsub;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * PubSubPublish constructor.
     * @param PubSubService $pubsub
     * @param EntityManager $em
     */
    public function __construct(PubSubService $pubsub, EntityManager $em)
    {
        parent::__construct();
        $this->pubsub = $pubsub;
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($message = $this->argument('message')) {

            case PubSubService::MESSAGE_SCHOLARSHIP_PUBLISHED:
                /** @var ScholarshipRepository $repository */
                $scholarshipId = $this->option('scholarshipId');
                $repository = $this->em->getRepository(Scholarship::class);
                $this->pubsub->pubScholarshipPublished($repository->findById($scholarshipId));
                break;

            case PubSubService::MESSAGE_SCHOLARSHIP_DEADLINE:
                /** @var ScholarshipRepository $repository */
                $scholarshipId = $this->option('scholarshipId');
                $repository = $this->em->getRepository(Scholarship::class);
                $this->pubsub->pubScholarshipDeadline($repository->findById($scholarshipId));
                break;

            case PubSubService::MESSAGE_APPLICATION_APPLIED:
                /** @var Application $application */
                $applicationId = $this->option('applicationId');
                $application = RestRepository::create($this->em, Application::class)->findById($applicationId);
                $this->pubsub->pubApplicationApplied($application);
                break;

            case PubSubService::MESSAGE_APPLICATION_WINNER:
                /** @var Application $application */
                $applicationId = $this->option('applicationId');
                $application = RestRepository::create($this->em, Application::class)->findById($applicationId);
                $this->pubsub->pubApplicationWinner($application);
                break;

            case PubSubService::MESSAGE_APPLICATION_WINNER_FILLED:
                /** @var Application $application */
                $applicationId = $this->option('applicationId');
                $application = RestRepository::create($this->em, Application::class)->findById($applicationId);
                $this->pubsub->pubApplicationWinnerFilled($application);
                break;

            case PubSubService::MESSAGE_APPLICATION_WINNER_PUBLISHED:
                /** @var Application $application */
                $applicationId = $this->option('applicationId');
                $application = RestRepository::create($this->em, Application::class)->findById($applicationId);
                $this->pubsub->pubApplicationWinnerPublished($application->getWinner()->getScholarshipWinner());
                break;

            case PubSubService::MESSAGE_APPLICATION_WINNER_DISQUALIFIED:
                /** @var Application $application */
                $applicationId = $this->option('applicationId');
                $application = RestRepository::create($this->em, Application::class)->findById($applicationId);
                $this->pubsub->pubApplicationWinnerDisqualified($application);
                break;

            default:
                throw new \InvalidArgumentException(sprintf('Event "%s" not exists.', $message));
                break;
        }
    }
}
