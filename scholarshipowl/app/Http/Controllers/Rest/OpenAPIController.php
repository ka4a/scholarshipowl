<?php

namespace App\Http\Controllers\Rest;

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
                'jsonFileUrl' => route('rest::v1.doc.file')
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getFilePath()
    {
        return  base_path().'/app/Rest/OpenApi/rest.yml';
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