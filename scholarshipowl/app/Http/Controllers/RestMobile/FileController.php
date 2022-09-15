<?php

namespace App\Http\Controllers\RestMobile;

use App\Http\Controllers\Controller;
use App\Services\FileService;


class FileController extends Controller
{
    /**
     * @var FileService
     */
    protected $fs;

    /**
     * FileController constructor.
     *
     * @param FileService $fs
     */
    public function __construct(FileService $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @param $path
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accountFileShow($path)
    {
        $accountFile = $this->getAccountFile($path);
        $this->authorize('show', $accountFile);

        return \Response::file($accountFile->getFileAsTemporary());
    }

    /**
     * @param $fileId
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accountFileDownload($fileId)
    {
        $accountFile = $this->getAccountFile($fileId);
        $this->authorize('show', $accountFile);

        return response()->download($accountFile->getFileAsTemporary(), $accountFile->getRealName());
    }

    /**
     * @param $fileId
     *
     * @return \App\Entity\AccountFile
     */
    private function getAccountFile($fileId): \App\Entity\AccountFile
    {
        return $this->fs->getAccountFileById($fileId, $this->getCurrentAccount());;
    }

    /**
     * @return \App\User|null
     */
    protected function getCurrentAccount(){
        return \Auth::user();
    }
}


