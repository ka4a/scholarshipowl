<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Middleware;

use App\Services\DomainService;
use Closure;
use Illuminate\Http\Request;
use App\Http\Traits\RestHelperTrait;
use App\Entity\Domain;

class DomainRoute
{
    use RestHelperTrait;

    protected $domainService = null;

    /**
     * @param DomainService $domainService
     */
    public function __construct(DomainService $domainService)
    {
        $this->domainService = $domainService;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param $domain
     * @return mixed
     */

    public function handle(Request $request, Closure $next, $domain)
    {
        $this->domainService->set($domain);
        return $next($request);
    }

}