<?php namespace App\Http\Middleware;

use App\Entity\MarketingSystem;
use App\Services\HasOffersService;
use Closure;
use Illuminate\Http\Request;

class TrackingParamsMiddleware
{
    const COOKIE_MARKETING_SYSTEM = 'marketing_system';

    /**
     * @var HasOffersService
     */
    protected $ho;

    /**
     * TrackingParamsMiddleware constructor.
     *
     * @param HasOffersService $ho
     */
    public function __construct(HasOffersService $ho)
    {
        $this->ho = $ho;
    }

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return $this
     */
    public function handle($request, Closure $next)
    {
        if (($request instanceof Request) && ($params = $this->ho->getTrackingParams($request))) {
            $data = serialize(['url_params' => $params]);

            HasOffersService::setCookieAffiliateId($params['affiliate_id'] ?? 'none');

            return $next($request)
                ->withCookie(cookie(static::COOKIE_MARKETING_SYSTEM, $data, 30 * 24 * 60));
        }

        return $next($request);
    }
}
