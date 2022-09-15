<?php

namespace App\Http\Controllers\RestExternal;

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
                'jsonFileUrl' => route('rest-external::v1.doc.file')
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function getFilePath()
    {
        return  base_path().'/app/Rest/OpenApi/rest-external.yml';
    }
}