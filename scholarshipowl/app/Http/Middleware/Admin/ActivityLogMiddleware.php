<?php namespace App\Http\Middleware\Admin;

use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminActivityLog;
use App\Services\Admin\ActivityLogger;
use Illuminate\Http\Request;

class ActivityLogMiddleware
{
    /**
     * @var ActivityLogger
     */
    protected $logger;

    /**
     * ActivityLogMiddleware constructor.
     *
     * @param ActivityLogger $logger
     */
    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request         $request
     * @param \Closure $next
     */
    public function handle($request, \Closure $next)
    {
        if (!\App::environment('testing') && $request instanceof Request) {
            if (($admin = \Auth::user()) && $admin instanceof Admin) {

                $this->logger->logRequest($admin, $request);

            }
        }

        return $next($request);
    }
}
