<?php namespace App\Http\Controllers\Admin;

use App\Entity\OnesignalNotification;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * NotificationController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(OnesignalNotification::class);
    }

    /**
     * @param string $app
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($app)
    {
        $this->addBreadcrumb($title = "Notification $app", 'notification.index', ['app' => $app]);

        $notifications = $this->repository->findBy(['app' => $app]);

        return $this->view($title, 'admin.notification.index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * @param Request   $request
     * @param string    $app
     * @param string    $type
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $app, $type)
    {
        /** @var OnesignalNotification $notification */
        $notification = $this->repository->findOneBy(['app' => $app, 'type' => $type]);

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'template_id'   => 'required_without_all:content,title',
                'title'         => 'required_with:content',
                'content'       => 'required_with:title',
                'active'        => 'required',
                'cap_amount'    => 'required',
                'cap_type'      => 'required',
                'cap_value'     => 'required',
                'delay_type'    => 'required',
                'delay_value'   => 'required',
            ], [
                'required_without_all' => 'Missing "template id" or "title" and "content".'
            ]);

            $notification->setTemplateId($request->get('template_id'));
            $notification->setTitle($request->get('title'));
            $notification->setContent($request->get('content'));
            $notification->setActive($request->get('active') === '1');

            $notification->setCapAmount($request->get('cap_amount'));
            $notification->setCapValue($request->get('cap_amount') > 0 ? $request->get('cap_value') : 0);
            $notification->setCapType($request->get('cap_type'));

            $notification->setDelayValue($request->get('delay_value'));
            $notification->setDelayType($request->get('delay_type'));

            $this->em->flush($notification);

            return \Redirect::route('admin::notification.edit', ['app' => $app, 'type' => $type])
                ->with(['message' => 'Successfully saved!']);
        }

        $this->addBreadcrumb("Notification $app", 'notification.index', ['app' => $app]);
        $this->addBreadcrumb($notification->getType(), 'notification.edit', ['app' => $app, 'type' => $type]);

        return $this->view($notification->getApp(), 'admin.notification.edit', [
            'notification' => $notification,
        ]);
    }
}
