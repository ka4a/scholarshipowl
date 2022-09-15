<?php

namespace App\Http\Middleware;

use Closure;

class WhiteList
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**
         * Disable whitelist until results cached.
         */
        if (\App::environment('production') && false) {
            try {

                if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
                    $countryCode = $_SERVER["HTTP_CF_IPCOUNTRY"];
                }else {
                    $adapter = new \Geocoder\HttpAdapter\CurlHttpAdapter();
                    $provider = new \Geocoder\Provider\GeoPluginProvider($adapter);
                    $geocoder = new \Geocoder\Geocoder($provider);
                    $ip = $request->header('CF_Connecting_IP') ? $request->header('CF_Connecting_IP') : $_SERVER['REMOTE_ADDR'];

                    $code = $geocoder->geocode($ip);
                    $countryCode = $code->getCountryCode();
                }

                $whitelist = setting('security.ip_whitelist');


                if (is_null($countryCode)) {
                    \Log::error('Country code is null');
                    return response('Not Found.', 404);
                }

                if (!empty($whitelist)) {
                    if (!in_array($countryCode, $whitelist)) {
                        \Log::info('[WHITELIST] Request from blacklisted country code', array('country code' => $countryCode));
                        return response('Not Found.', 404);
                    }
                }
            }
            catch (\Exception $exc) {
                \Log::error($exc);
            }
        }

        return $next($request);
    }
}
