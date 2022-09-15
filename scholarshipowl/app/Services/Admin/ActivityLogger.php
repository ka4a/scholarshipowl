<?php namespace App\Services\Admin;

use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminActivityLog;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

class ActivityLogger
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * List of GET routes that should be logged.
     *
     * @var array
     */
    protected $getRoutes = [
        'admin/*copy*',
        'admin/*mark*',
        'admin/*unmark*',
        'admin/*delete*',
        'admin/*cancel*',
        'admin/*activate*',
        'admin/*deactivate*',
        'admin/export/*',
    ];

    /**
     * List of fields that should be blanked out on saving.
     *
     * @var array
     */
    protected $blankOut = ['password', 'retype_password'];

    /**
     * ActivityLogger constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Admin   $admin
     * @param Request $request
     *
     * @return AdminActivityLog
     */
    public function logRequest(Admin $admin, Request $request)
    {
        $activity = null;

        if ($this->isLogable($request)) {
            $activity = new AdminActivityLog(
                $admin,
                $this->getRoute($request),
                $this->getData($request)
            );

            $this->em->persist($activity);
            $this->em->flush($activity);
        }

        return $activity;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isLogable(Request $request)
    {
        if ($request->isMethod('post') || $request->isMethod('put')) {
            return true;
        }

        if ($request->isMethod('get') && call_user_func_array([$request, 'is'], $this->getRoutes)) {
            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->all();

        foreach ($this->blankOut as $item) {
            if (array_key_exists($item, $data)) {
                $data[$item] = '*****';
            }
        }

        return json_encode($data);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    protected function getRoute(Request $request)
    {
        return sprintf('%s /%s', $request->method(), $request->path());
    }
}
