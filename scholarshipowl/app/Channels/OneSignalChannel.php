<?php namespace App\Channels;

use App\Entity\Account;
use App\Entity\OnesignalAccount;
use App\Entity\OnesignalNotification;
use App\Contracts\OnesignalNotificationContract;
use App\Entity\OnesignalNotificationSent;
use Doctrine\ORM\EntityManager;

use GuzzleHttp\Client as GuzzleClient;
use Http\Client\Common\HttpMethodsClient as HttpClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use OneSignal\Config;
use OneSignal\OneSignal;

class OneSignalChannel
{
    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;

    /**
     * @var array|OneSignal[]
     */
    protected $clients = [];

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $onesignalNotifications;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $onesignalAccounts;

    /**
     * OneSignalChannel constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->onesignalNotifications = $em->getRepository(OnesignalNotification::class);
        $this->onesignalAccounts = $em->getRepository(OnesignalAccount::class);
    }

    /**
     * @param Account|array[int]            $accounts
     * @param OnesignalNotificationContract $notification
     */
    public function send($accounts, OnesignalNotificationContract $notification)
    {
        $accounts = ($accounts instanceof Account) ? [$accounts->getAccountId()] : $accounts;

        /** @var OnesignalNotification[] $notifications */
        $notifications = $this->onesignalNotifications->findBy([
            'type'   => $notification->getType(),
            'active' => true
        ]);

        foreach ($notifications as $osNotification) {
            $players = $this->getPlayerIds($osNotification->getApp(), $notification->getType(), $accounts);

            foreach ($players as $accountId => $ids) {

                /** @var Account $account */
                $account = $this->em->find(Account::class, $accountId);

                $options = [
                    'include_player_ids' => $ids,
                    'data' => $notification->getData(),
                ];

                if ($osNotification->getTemplateId()) {
                    $options['template_id'] = $osNotification->getTemplateId();
                } else if ($osNotification->getTitle() && $osNotification->getContent()) {
                    $options['contents'] = ['en' => $notification->mapTags($osNotification->getContent(), $account)];
                    $options['headings'] = ['en' => $notification->mapTags($osNotification->getTitle(), $account)];
                }

                if ($osNotification->getDelayValue()) {
                    $options['send_after'] = new \DateTime($osNotification->getDelayString());
                }

                $this->sendNotification($osNotification->getApp(), $notification->getType(), $options);
            }
        }
    }

    /**
     * @param string     $app
     * @param int        $type
     * @param array      $options
     */
    protected function sendNotification($app, $type, array $options)
    {
        if (is_production()) {
            $result = $this->oneSignalClient($app)->notifications->add($options);

            if (isset($result['errors'])) {
                throw new \RuntimeException(implode("\n", $result['errors']));
            }
        }

        if (isset($options['include_player_ids'])) {
            foreach ($options['include_player_ids'] as $player) {
                $this->em->persist(new OnesignalNotificationSent($app, $player, $type));
            }

            $this->em->flush();
        }
    }

    /**
     * Get players ids to send. Filter them by cap period.
     *
     * @param string     $app
     * @param int        $type
     * @param array      $accounts
     *
     * @return array
     */
    protected function getPlayerIds($app, $type, array $accounts)
    {
        $players = [];
        $result = $this->onesignalAccounts->createQueryBuilder('osa')
            ->select(['IDENTITY(osa.account) AS accountId', 'osa.userId'])
            ->leftJoin(OnesignalNotification::class, 'osn', 'WITH', 'osn.app = osa.app AND osn.type = :type')
            ->setParameter('accounts', $accounts)
            ->setParameter('type', $type)
            ->setParameter('app', $app)
            ->setParameter('now', new \DateTime())
            ->andWhere('osa.account IN (:accounts) AND osa.app = :app')
            ->andWhere(sprintf(
                '(osn.capAmount = 0 OR ((
                    SELECT COUNT(osns.id)
                    FROM %s osns
                    WHERE
                        osns.app = osn.app AND osns.userId = osa.userId AND osns.type = osn.type AND
                        (osn.capValue = 0 OR
                        (osn.capType = \'minute\' AND osns.createdAt > DATESUB(:now, osn.capValue, \'MINUTE\')) OR
                        (osn.capType = \'hour\'   AND osns.createdAt > DATESUB(:now, osn.capValue, \'HOUR\')) OR
                        (osn.capType = \'day\'    AND osns.createdAt > DATESUB(:now, osn.capValue, \'DAY\')) OR
                        (osn.capType = \'week\'   AND osns.createdAt > DATESUB(:now, osn.capValue, \'WEEK\')) OR
                        (osn.capType = \'month\'  AND osns.createdAt > DATESUB(:now, osn.capValue, \'MONTH\')) OR
                        (osn.capType = \'year\'   AND osns.createdAt > DATESUB(:now, osn.capValue, \'YEAR\')))
                ) < osn.capAmount))',
                OnesignalNotificationSent::class
            ))
            ->getQuery()
            ->getScalarResult();

        foreach ($result as $row) {
            $players[$row['accountId']][] = $row['userId'];
        }

        return $players;
    }

    /**
     * @param string $app
     *
     * @return OneSignal
     */
    protected function oneSignalClient($app)
    {
        if (!isset($this->clients[$app])) {
            $config = new Config();
            $config->setApplicationId(config("onesignal.$app.app_id"));
            $config->setApplicationAuthKey(config("onesignal.$app.api_key"));

            $client = new HttpClient(new GuzzleAdapter(), new GuzzleMessageFactory());
            $this->clients[$app] = new OneSignal($config, $client);
        }

        return $this->clients[$app];
    }

    /**
     * @return GuzzleClient
     */
    protected function getGuzzleClient()
    {
        if ($this->guzzleClient === null) {
            $this->guzzleClient = new GuzzleClient();
        }

        return $this->guzzleClient;
    }
}
