<?php

namespace App\Http\Controllers\RestMobile;

use App\Http\Controllers\OpenAPIAbstractController;
use Illuminate\Http\Request;

class OpenAPIController extends OpenAPIAbstractController
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return \View::make(
            'openApi.swagger',
            [
                'jsonFileUrl' => route('rest-mobile::v1.doc.file')
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getFilePath()
    {
        return  base_path().'/app/Rest/OpenApi/rest-mobile.yml';
        //return  base_path().'/app/Rest/OpenApi/t.yaml';
    }

    /**
     * @inheritdoc
     */
    public static function getIncludeFileContent($fileName)
    {
        return \Response::file(base_path()."/app/Rest/OpenApi/{$fileName}", [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
        ]);
    }

}