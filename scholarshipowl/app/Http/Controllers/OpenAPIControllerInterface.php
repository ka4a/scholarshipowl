<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

interface  OpenAPIControllerInterface
{
    /**
     * Returns response with open-api json file content
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getFileContent();

    /**
     * Absolute path to open-api json file
     *
     * @return string
     */
    public static function getFilePath();
}