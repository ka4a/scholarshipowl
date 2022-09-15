<?php

namespace App\Http\Controllers\Index;

use App\Entity\Application;
use App\Entity\Eligibility;
use App\Entity\Field;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Entity\Winner;
use App\Events\Scholarship\ScholarshipApplicationDeclinedEvent;
use App\Events\Scholarship\ScholarshipDisqualifiedWinnerEvent;
use App\Events\Scholarship\ScholarshipPotentialWinner;
use App\Events\Scholarship\ScholarshipPotentialWinnerEvent;
use App\Events\Scholarship\ScholarshipProvedWinnerEvent;
use App\Facades\EntityManager;
use App\Services\ScholarshipService;
use Google\Cloud\PubSub\Message;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PubSubSunriseApplicationEndpointController extends PubSubSunrisePushEndpointBaseController
{
    const EVENT_LIST = [
        'application.applied',
        'application.status_changed',
        'application.winner',
        'application.winner_filled',
        'application.winner_disqualified',
        'application.winner_published',
    ];

    /**
     * uuid of external application
     *
     * @var string
     */
    public $externalApplicationId;

    /**
     * uuid if external scholarship
     *
     * @var string
     */
    public $externalScholarshipId;

    /**
     * Manage external application status and scholarship transitional status.
     *
     * @param Request $request
     * @return Response
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \ReflectionException
     */
    public function actualizeApplication(Request $request): Response
    {
        $this->handleRequest($request);

        $externalApplicationId = $this->externalApplicationId;
        $externalScholarshipId = $this->externalScholarshipId;

        /** @var Scholarship $scholarship */
        $scholarship = EntityManager::getRepository(Scholarship::class)
            ->findOneBy(['externalScholarshipId' => $externalScholarshipId]);

        $data = json_decode($this->message->data(), true);

        if ($scholarship) {
            $prevStatus = $scholarship->getTransitionalStatus();

            if ($this->event == 'application.winner') {
                $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_POTENTIAL_WINNER);
            } else if ($this->event == 'application.winner_filled') {
                $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER);
            } else if ($this->event == 'application.winner_disqualified') {
                $scholarship->setTransitionalStatus(Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER);
            }

            if (isset($data['url_winner_information'])) {
                $scholarship->setWinnerFormUrl($data['url_winner_information']);
            }

            EntityManager::flush($scholarship);

            if ($prevStatus != $scholarship->getTransitionalStatus()) {
                \Log::info(
                    sprintf(
                        'Updated transitional status for Sunrise scholarship [ %s ] from [ %s ] to [ %s ]',
                        $externalScholarshipId, $prevStatus, $scholarship->getTransitionalStatus()
                    )
                );
            }
        }

        /** @var Application $application */
        $application = EntityManager::getRepository(Application::class)
            ->findOneBy(['externalApplicationId' => $externalApplicationId]);

        if (!$application) {
            // do nothing, not all application events targeted to SOWL
            return response('', 204);
        }


        if ($this->event === 'application.status_changed') {
            if ($data['status'] === 'rejected') {
                $application->setExternalStatus(Application::EXTERNAL_STATUS_DECLINED);
                EntityManager::persist($application);
                \Event::dispatch(new ScholarshipApplicationDeclinedEvent($scholarship, $application));

                EntityManager::flush($application);
            }
        } else if ($this->event === 'application.winner_published') {
            $this->addWinner();
        } else {
            if ($this->event === 'application.applied') {
                $application->setExternalStatus(Application::EXTERNAL_STATUS_ACCEPTED);
            } else if ($this->event === 'application.winner') {
                $application->setExternalStatus(Application::EXTERNAL_STATUS_POTENTIAL_WINNER);
                EntityManager::persist($application);
                \Event::dispatch(new ScholarshipPotentialWinnerEvent($scholarship, $application));
            } else if ($this->event === 'application.winner_filled') {
                $application->setExternalStatus(Application::EXTERNAL_STATUS_PROVED_WINNER);
                EntityManager::persist($application);
                \Event::dispatch(new ScholarshipProvedWinnerEvent($scholarship, $application));
            } else if ($this->event === 'application.winner_disqualified') {
                $application->setExternalStatus(Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER);
                EntityManager::persist($application);
                \Event::dispatch(new ScholarshipDisqualifiedWinnerEvent($scholarship, $application));
            }

            EntityManager::flush($application);
        }

        \Log::info(
            sprintf(
                'Updated status for Sunrise Application with external id [ %s ]. PubSub message: %s ',
                $externalApplicationId,
                var_export($this->rawMessage, true)
            )
        );

        return response('', 204);
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function addWinner()
    {
        /** @var Scholarship $scholarship */
        $scholarship = EntityManager::getRepository(Scholarship::class)
            ->findOneBy(['externalScholarshipId' => $this->externalScholarshipId]);

        /** @var Application $application */
        $application = EntityManager::getRepository(Application::class)
            ->findOneBy(['externalApplicationId' => $this->externalApplicationId]);

        if (!$application) {
            \Log::info(
                sprintf(
                    'Skip publishing non-sowl winner of a scholarship [ %s ] and application [ %s ]',
                    $this->externalScholarshipId, $this->externalApplicationId
                )
            );

            return response('', 204);
        }

        $data = json_decode($this->message->data(), true);

        $winner = \EntityManager::getRepository(Winner::class)->findOneBy([
            'scholarship' => $scholarship,
            'account' => $application->getAccount()
        ]);

        if (!$winner) {
            $winner = new Winner();
        }

        $winner->setScholarship($scholarship);
        $winner->setAccount($application->getAccount());
        $winner->setScholarshipTitle($scholarship->getTitle());
        $winner->setAmountWon($scholarship->getAmount());
        $winner->setWonAt(new \DateTime($data['winner']['wonDate']));
        $winner->setWinnerName($data['winner']['name']);
        $winner->setTestimonialText($data['winner']['testimonial']);
        $winner->setPublished(true);

        $imgUrl = $data['winner']['imageUrl'];
        $imgFile = file_get_contents($imgUrl);
        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->buffer($imgFile);
        $ext = strstr($mimeType, 'image/');

        $gcPath = '/winners/winner_photo/'.uniqid().'.'.$ext;
        \Storage::disk('gcs')->put(
            $gcPath,
            $imgFile,
            \Illuminate\Contracts\Filesystem\Filesystem::VISIBILITY_PUBLIC
        );

        $winner->setWinnerPhoto(\App\Facades\Storage::public($gcPath));

        \EntityManager::persist($winner);
        \EntityManager::flush($winner);
    }

    /**
     * Validates PubSub message and creates Message instance.
     * Response with 204 status code implies implicit message ack.
     *
     * @param Request $request
     */
    protected function handleRequest(Request $request): void
    {
        parent::handleRequest($request);

        $this->externalApplicationId = $this->message->attribute('id');
        $this->externalScholarshipId = $this->message->attribute('scholarship_id');

        if (!$this->externalApplicationId) {
            $msg = 'Sunrise application must have an id';
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        $this->event = $this->message->attribute('event');
        if (!in_array($this->event, self::EVENT_LIST)) {
            $msg = sprintf('Unknown event [ %s ]', $this->event);
            $this->logValidationError($msg);
            abort(400, $msg);
        }

        if (!$this->maintainIdempotency("actualizeApplication.timestamp.{$this->externalApplicationId}", $this->message->attribute('timestamp'))) {
            $msg = sprintf(
                'Event for application with external id [ %s ] discarded because a later message was already processed',
                $this->externalApplicationId
            );
            $this->logValidationError($msg);

            abort(204);
        }
    }
}

