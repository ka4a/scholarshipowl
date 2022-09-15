<?php

namespace App\Http\Controllers\Index;

class SrvController extends \Illuminate\Routing\Controller
{
    /**
     * Sets SRV cookie and redirects to previous URL.
     *
     * @param string $serverName prod or canary
     * @return $this
     */
    public function setCookie($serverName)
    {
        $serverName = strtolower($serverName);
        $serverName = in_array($serverName, ['prod', 'canary']) ? $serverName : null;

        $url = \Request::url() === \URL::previous() ? route('homepage') : \URL::previous();

        if ($serverName) {
            $cookie = cookie('SRV', $serverName);
            return \Redirect::to($url)->withCookie($cookie);
        }

        return \Redirect::to($url);
    }

    /**
     * Unset SRV cookie and redirects to previous URL.
     *
     * @return $this
     */
    public function clearCookie()
    {
        $url = \Request::url() === \URL::previous() ? route('homepage') : \URL::previous();
        return \Redirect::to($url)->withCookie(\Cookie::forget('SRV'));
    }

}
