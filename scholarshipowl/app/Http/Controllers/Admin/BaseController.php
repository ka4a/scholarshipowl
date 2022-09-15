<?php namespace App\Http\Controllers\Admin;

use ScholarshipOwl\Http\AbstractController;

/**
 * Base Controller for admin
 */
class BaseController extends AbstractController
{

    const BASIC_ROUTE_PREFIX = 'admin::';

    /**
     * @var string
     */
    protected $authGuard = 'admin';

    /**
     * @var array
     */
    protected $breadcrumbs = [];

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->addBreadcrumb('Dashboard', 'index.index');
    }

    /**
     * @return \App\Entity\Admin\Admin|null
     */
    protected function getLoggedUser() {
        return \Auth::guard('admin')->user();
    }

    /**
     * @param string $title
     * @param string $view
     * @param array  $data
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function view(string $title, string $view, array $data = [])
    {
        $route = \Route::getCurrentRoute()->getName();

        return \View::make($view, array_merge_recursive([
            'user' => $this->getLoggedUser(),
            'title' => $title,
            'breadcrumb' => $this->getBreancrumbs(),
            'active' => str_before('.', str_after(static::BASIC_ROUTE_PREFIX, $route)),
        ], $data));
    }

    /**
     * @param string $title
     * @param string $route
     * @param mixed  $params
     */
    protected function addBreadcrumb(string $title, string $route, $params = null)
    {
        $this->breadcrumbs[$title] = \URL::route(static::BASIC_ROUTE_PREFIX.$route, $params);
        return $this;
    }

    /**
     * Add breadcrumb for post action
     *
     * @param string $route
     * @param null   $params
     */
    protected function addPostBreadcrumb(string $route, $params = null)
    {
        $this->addBreadcrumb($params ? 'Edit' : 'Create', $route, $params);
        return $this;
    }

    /**
     * @return array
     */
    protected function getBreancrumbs()
    {
        return $this->breadcrumbs;
    }

    /**
     * Should not be used.
     *
     * @access protected
     * @param \Exception $exc
     * @return void
     *
     * @deprecated
     * @author Marko Prelic <markomys@gmail.com>
     */
    protected function handleException($exc)
    {
        if ($exc instanceof \Exception) {
            /** @var \Raven_Client $sentry */
            $sentry = app('sentry');
            $sentry->captureException($exc);
        }
        handle_exception($exc);
    }
}
