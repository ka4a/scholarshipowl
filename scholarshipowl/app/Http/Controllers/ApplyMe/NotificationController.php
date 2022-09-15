<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Controllers\ApplyMe;

use App\Entity\Account;
use App\Entity\OnesignalAccount;
use App\Entity\Repository\EntityRepository;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Entity\PushNotifications;
use App\Entity\Resource\ApplyMe\PushNotificationsResource;

class NotificationController extends Controller
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $onesignalAccountRepo;

    /**
     * @var EntityRepository
     */
    protected $pushRepo;

    /**
     * NotificationController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->initRepos();
    }

    protected function initRepos()
    {
        $this->onesignalAccountRepo = $this->em->getRepository(OnesignalAccount::class);
        $this->pushRepo = $this->em->getRepository(PushNotifications::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'userId'   => 'required|string',
            'provider' => 'required|string'
        ]);

        /** @var Account $account */
        $account = \Auth::user();

        /** @var OnesignalAccount $notification */
        $notification = $this->onesignalAccountRepo->findOneBy(['account' => $account->getAccountId()]);

        if ($notification != null) {
            $notification->setUserId($request->input('userId'));
        } else {
            $notification = new OnesignalAccount(
                $account,
                $request->input('userId'),
                $request->input('provider')
            );
           $this->em->persist($notification);
        }

        $this->em->flush();

        return $this->jsonSuccessResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showNotification(Request $request)
    {
        $this->validate($request, [
            'slug' => 'required|string'
        ]);

        $notification = $this->pushRepo->findOneBy(['slug' => $request->input('slug')]);

        if (!$notification) {
            return $this->jsonErrorResponse('Invalid slug', 404);
        }

        $pushNotificationResource = new PushNotificationsResource($notification);

        return $this->jsonDataResponse($pushNotificationResource->toArray());
    }
}
