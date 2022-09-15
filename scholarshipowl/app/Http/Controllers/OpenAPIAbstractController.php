<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

abstract class OpenAPIAbstractController  extends Controller implements OpenAPIControllerInterface
{
    /**
     * @inheritdoc
     */
    abstract static function getFilePath();


    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getFileContent()
    {
        return \Response::file(static::getFilePath(), [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'
        ]);
    }
}