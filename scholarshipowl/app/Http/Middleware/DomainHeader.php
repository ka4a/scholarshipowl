<?php
# CrEaTeD bY FaI8T IlYa      
# 2016  

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Entity\Domain;

class DomainHeader
{
    const HEADER = 'X-Sowl-Domain';

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader(static::HEADER)) {
            \Domain::set($this->findDomainByName($request->header(static::HEADER)));
        }

        return $next($request);
    }

    /**
     * @param string $domain
     * @return Domain
     */
    protected function findDomainByName(string $domain)
    {
        return Domain::findOneBy(['name' => strtolower($domain)]);
    }
}