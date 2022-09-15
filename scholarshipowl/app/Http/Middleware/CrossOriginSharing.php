<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\HeaderBag;

class CrossOriginSharing
{
    /**
     * Allow CORS by domain provided like an option.
     * Multiple domains can be separated by "|".
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string   $option
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $option)
    {
        $allow = $option === '*' ? '*' : false;
        $domains = explode('|', $option);
        $referer = $request->headers->get('referer');

        if (is_array($domains)) {
            foreach ($domains as $domain) {
                if (strpos($referer, $domain) !== false) {
                    $allow = 'http://'.$domain;
                    break;
                }
            }
        }

        abort_unless($allow, 404);

        return $this->allowCORS($next($request), $allow, true);
    }

    /**
     * Add CORS allow headers to response
     *
     * @param object  $response
     * @param string  $url
     * @param bool    $withOptions
     *
     * @return object
     */
    protected function allowCORS($response, string $url = '*', bool $withOptions = false)
    {
        if ($response && $response->headers instanceof HeaderBag) {
            $response->headers->set('Access-Control-Allow-Origin', $url);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');

            if ($withOptions) {
                $response->headers->set(
                    'Access-Control-Allow-Methods',
                    'PUT, GET, POST, DELETE, OPTIONS'
                );
                $response->headers->set(
                    'Access-Control-Allow-Headers',
                    'X-CSRF-Token, origin, x-requested-with, content-type, X-PINGOTHER'
                );
            }
        }

        return $response;
    }
}
