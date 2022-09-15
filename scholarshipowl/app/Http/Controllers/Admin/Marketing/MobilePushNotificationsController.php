<?php namespace App\Http\Controllers\Admin\Marketing;

use App\Entity\Account;
use App\Entity\Marketing\MobilePushNotificationSettings;
use App\Entity\Repository\EntityRepository;
use App\Entity\TransactionalEmail;
use App\Http\Controllers\Admin\BaseController;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Util\Mailer;

class MobilePushNotificationsController extends BaseController
{
    use ValidatesRequests;

    const ACTIVATED_STATUS = 'activate';
    const DEACTIVATED_STATUS = 'deactivate';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(MobilePushNotificationSettings::class);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function mobilePushNotificationsListAction()
    {
        $this->addBreadcrumb('Marketing', 'marketing.index');
        $this->addBreadcrumb('Mobile push notifications', 'marketing.mobile_push_notifications.mobilePushNotificationsList');

        return $this->view('Marketing - Mobile push notifications', 'admin.marketing.mobile_push_notifications.list', [
            "mobilePushNotifications" => $this->repository->findAll(),
        ]);
    }

    /**
     * @param $id
     * @param $status
     * @return mixed
     */
    public function switchStatusAction($id, $status)
    {
        $url = "admin::marketing.mobile_push_notifications.mobilePushNotificationsList";

        $availableStatuses = [self::ACTIVATED_STATUS, self::DEACTIVATED_STATUS];
        $message = ['error' => "$status is not a valid status."];

        if (in_array($status, $availableStatuses)) {
            try {
                /**
                 * @var MobilePushNotificationSettings $setting
                 */
                $setting = $this->repository->findOneBy(['pushNotificationId' => $id]);
                $message = ['error' => "Can't find push notification with ID $id"];
                if (!is_null($setting)) {
                    $setting->switchStatus();
                    $this->em->persist($setting);
                    $this->em->flush();

                    $message = ['message' => "Push notification with ID $id now is $status"];
                }

            } catch (\Exception $e) {
                $message = ['error' => "Can't update push notification setting."];
            }
        }

        return \Response::redirectToRoute($url, ['id' => $id])->with($message);
    }

}
