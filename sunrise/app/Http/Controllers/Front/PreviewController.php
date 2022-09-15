<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function index(Request $request)
    {
        try {
            $url = sprintf('%s/preview', config('sunrise.barn.url'));
            $http = new Client();
            $response = $http->post($url, [
                'body' => json_encode($request->all()),
                'headers' => [
                    'Content-Type' => 'application-json',
                ]
            ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return \GuzzleHttp\json_decode($response->getBody(), true)['content'] ?: '';
    }
}
