<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Facades\EntityManager;
use App\Services\PubSub\TransactionalEmailService;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FireBaseService
{

    /**
     * @var Firebase
     */
    protected $fireBase;

    protected $apnsConfig = [
        'sound' => 'default',
        'badge' => 1,
    ];

    protected $notificationConfig;

    /**
     * @var ScholarshipRepository $scholarshipRepository
     */
    protected $scholarshipRepository;

    /**
     * FireBaseService constructor.
     */
    public function __construct()
    {
        $this->scholarshipRepository = EntityManager::getRepository(Scholarship::class);

        $serviceAccount = Firebase\ServiceAccount::fromJsonFile(config('filesystems.disks.gcs.mobile_key_file'));

        $this->fireBase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();

        $this->notificationConfig = [
            TransactionalEmailService::SCHOLARSHIP_USER_WON => [
                'title' => "Woo-HOOT!",
                'body' =>  "Rockstar, you won %scholarshipTitle! Send data in the next 72 hours to swoop your scholarship award. Congrats!"
            ],

            TransactionalEmailService::SCHOLARSHIP_USER_AWARDED => [
                'title' => 'Flight of glory!',
                'body' =>  "You got the %scholarshipTitle award! Enjoy the well-deserved prize!"
            ],

            TransactionalEmailService::SCHOLARSHIP_WINNER_CHOSEN => [
                'title' => 'Hoo won?',
                'body' =>  "The winner of a %scholarshipTitle has been chosen. Find out if it is you!"
            ],

            TransactionalEmailService::SCHOLARSHIP_USER_MISSED => [
                'title' => 'So close… Nest time will be better',
                'body' =>  "Unfortunately, the %scholarshipTitle has flown away. Make sure you respond in time."
            ],
        ];
    }

    /**
     * @param $deviceToken
     * @param Firebase\Messaging\Notification $notification
     * @param array $data
     * @return Firebase\Messaging\CloudMessage
     * @throws \Exception
     */
    protected function prepareFirebaseMessage($deviceToken, Firebase\Messaging\Notification $notification, array $data)
    {
        $result = false;
        if (is_null($deviceToken) || empty($deviceToken)) {
            return false;
        }

        try {
            $result = Firebase\Messaging\CloudMessage::fromArray([
                'token' => $deviceToken,
            ])->withData($data)
                ->withNotification($notification)
                ->withApnsConfig($this->getApnsConfig());
        } catch (\Exception $e){
            \Sentry::captureException($e);
            \Log::error('Firebase service error occurred ' . " - " . $e->getMessage());
        }
        return $result;
    }

    /**
     * @param Firebase\Messaging\CloudMessage $message
     */
    public function sendMessage(Firebase\Messaging\CloudMessage $message)
    {
        $messaging = $this->fireBase->getMessaging();

        try{
            $messaging->send($message);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    /**
     * @return Firebase\Messaging\ApnsConfig
     */
    protected function getApnsConfig() {
        return Firebase\Messaging\ApnsConfig::fromArray([
            'headers' => [
                'apns-priority' => '10',
            ],
            'payload' => [
                'aps' => [
                    'sound' => 'default',
                    'badge' => 1,
                ],
            ],
        ]);
    }

    /**
     * @param string $event
     * @param Scholarship $scholarship
     * @return Firebase\Messaging\Notification
     * @throws \Exception
     */
    protected function prepareScholarshipEventNotification(string $event, Scholarship $scholarship) {
        if (!isset($this->notificationConfig[$event])) {
            throw new \Exception("Can't find notification config for event: $event");
        }

        $notificationConfig = $this->notificationConfig[$event];
        $title = $notificationConfig['title'];

        $body = str_replace( '%scholarshipTitle', $scholarship->getTitle(), $notificationConfig['body']);

        $notification =  Firebase\Messaging\Notification::fromArray([
            'title' => $title,
            'body' => $body
        ]);

        return $notification;
    }

    /**
     * @param Account $account
     * @param string $event
     * @param Application $application
     * @throws \Exception
     */
    public function sendScholarshipEventToUser(Account $account, string $event, Application $application)
    {
        $scholarship = $application->getScholarship();
        $notification = $this->prepareScholarshipEventNotification($event, $scholarship);
        $derivedStatus = $this->scholarshipRepository->getApplicationDerivedStatus($application);

        $data  = [
            'scholarshipId' => strval($scholarship->getScholarshipId()),
            'notificationId' => "notification.application.open",
            'isSentApplication' => "true",
            'status' => strval($derivedStatus)
        ];

        $this->sendMessageToUser($account, $notification, $data);
    }

    /**
     * @param Account $account
     * @param $notification
     * @param array $messageData
     * @throws \Exception
     */
    public function sendMessageToUser(Account $account, $notification, array $messageData)
    {
        if(is_array($notification)) {
            $notificationResolver = new OptionsResolver();
            $notificationResolver->setRequired([
                'body', 'title'
            ]);

            $notification = Firebase\Messaging\Notification::fromArray([
                'title' => $notification['title'],
                'body' => $notification['body']
            ]);
        }

        $cloudMessage = $this->prepareFirebaseMessage($account->getDeviceToken(), $notification, $messageData);

        if($cloudMessage) {
            $this->sendMessage($cloudMessage);

            \Log::info(
                sprintf(
                    'Sent push notification for user  %s .Firebase message: %s',
                    $account->getAccountId(),
                    var_export($cloudMessage->jsonSerialize(), true)
                )
            );
        }
    }
}

