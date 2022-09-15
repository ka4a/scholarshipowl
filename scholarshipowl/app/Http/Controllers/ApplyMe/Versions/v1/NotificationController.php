<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Controllers\ApplyMe\Versions\v1;

use App\Entity\Account;
use App\Entity\Repository\EntityRepository;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Entity\Installations;
use App\Entity\PushNotifications;
use App\Entity\Resource\ApplyMe\PushNotificationsResource;
use Doctrine\ORM\EntityManager;

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
    protected $instaRepo;

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
        $this->instaRepo = $this->em->getRepository(Installations::class);
        $this->pushRepo = $this->em->getRepository(PushNotifications::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'deviceToken' => 'required|string',
            'provider'    => 'required|string'
        ]);

        /** @var Account $account */
        $account = \Auth::user();

        /** @var Installations $token */
        $notification = $this->instaRepo->findOneBy(['account' => $account->getAccountId()]);

        if ($notification != null) {
            $notification->setDeviceToken($request->input('deviceToken'));
        } else {
            $notification = new Installations(
                $request->input('deviceToken'),
                $request->input('provider'),
                $account
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
