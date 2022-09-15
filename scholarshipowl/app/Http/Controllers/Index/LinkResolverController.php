<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;

class LinkResolverController extends \Illuminate\Routing\Controller
{
    public function resolveMagicLink($token, Request $request)
    {
        $appRedirect = $request->get('app-redirect');
        $webRedirect = $request->get('web-redirect');

        $prams = [$token];
        $appParams = ['magic-token' => $token];

        if ($webRedirect) {
            $prams['redirect'] = $webRedirect;
            $appParams['redirect'] = trim(urldecode($webRedirect), '/');
        }

        $webLink = route('rest::v1.auth.magicLink', $prams);

        if (!is_mobile()) {
          return redirect()->to($webLink);
        }

        if ($appRedirect) {
            $appRedirect = trim($appRedirect, '/');
            $appLink = "sowl://$appRedirect";
        } else {
            $appLink = 'sowl://handle-magic-link';
        }

        $appLink .= '?'.http_build_query($appParams);

        return view('rest.is-app-installed', compact('webLink', 'appLink'));
    }

    public function resolveLink(Request $request)
    {
        $appRedirect = $request->get('app-redirect');
        $webLink = urldecode($request->get('web-redirect', '/'));

        if (!is_mobile() || !$appRedirect) {
            return redirect()->to($webLink);
        }

        $appLink = 'sowl://'.trim(urldecode($appRedirect), '/');

        // for some unknown reason it might be left after decoding, so clean it up
        $appLink = str_replace('%2', '', $appLink);

        return view('rest.is-app-installed', compact('webLink', 'appLink'));
    }
}
