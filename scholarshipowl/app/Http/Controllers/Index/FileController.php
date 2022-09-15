<?php

namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\Essay;
use App\Entity\EssayFiles;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Home Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class FileController extends BaseController
{

    /**
     * @var FileService
     */
    protected $fs;

    public function __construct(FileService $fs)
    {
        parent::__construct();
        $this->fs = $fs;
    }

    private static $mime = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppt' => 'application/vnd.ms-powerpointtd',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'ps' => 'application/postscript',
        'rdf' => 'application/rdf',
        'rtf' => 'application/rtf',
        'txt' => 'text/plain',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    /**
     * @param $path
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function accountFileShow($path)
    {

        $accountFile = $this->fs->getAccountFileByPath($path, $this->getCurrentAccount());
        $this->authorize('show', $accountFile);

        return \Response::file($accountFile->getFile());
    }

    /**
     * @param $fileId
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function downloadAction($fileId)
    {
        $accountFile = $this->fs->getAccountFileById($fileId, $this->getCurrentAccount());
        $this->authorize('show', $accountFile);

        return response()->streamDownload(function () use($accountFile) {
            echo $accountFile->getFileContent();
        }, $accountFile->getRealName());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxUploadAction(Request $request)
    {
        $files = $request->file('files');
        $responseFiles = [];
        if (null !== ($account = \Auth::user()) && ($account instanceof Account)  ) {

            if ($files && is_array($files)) {
                /** @var UploadedFile $file */
                foreach ($files as $file) {
                    $accountFile = new AccountFile($file, $account, $file->getClientOriginalName());
                    \EntityManager::persist($accountFile);
                    \EntityManager::flush();

                    $responseFiles['files'][] = [
                        'id' => $accountFile->getId(),
                        'url' => $accountFile->getPublicUrl(),
                        'name' => $accountFile->getFileName(),
                    ];
                }
            }
        }

        return new JsonResponse($responseFiles);
    }

    /**
     * @param $fileId
     * @return JsonResponse
     */
    public function editFileName($fileId)
    {
        /** @var AccountFile $accountFile */
        $repository = \EntityManager::getRepository(AccountFile::class);
        if (null === ($accountFile = $repository->find($fileId))) {
            throw new NotFoundHttpException();
        }

        try {
            $accountFile->setFileName(\Input::get('file_name'));
            \EntityManager::flush($accountFile);

            return new JsonResponse(['status' => 'ok']);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Something went wrong']);
        }
    }

    /**
     * @param $fileId
     * @return JsonResponse
     */
    public function deleteAction($fileId)
    {
        /** @var AccountFile $accountFile */
        $repository = \EntityManager::getRepository(AccountFile::class);
        if (null === ($accountFile = $repository->find($fileId))) {
            throw new NotFoundHttpException();
        }

        \EntityManager::remove($accountFile);
        \EntityManager::flush();

        return new JsonResponse(['status' => 'ok']);
    }


    /**
     * @return JsonResponse
     */
    public function attachFileToEssay()
    {
        if (null !== ($account = \Auth::user()) && ($account instanceof Account)  ) {
            /** @var Essay $essay */
            $essay = \EntityManager::findById(Essay::class, \Input::get('essay_id'));
            /** @var AccountFile $file */
            $file = \EntityManager::findById(AccountFile::class, \Input::get('file_id'));
            $essayFile = new EssayFiles($essay, $file);

            try {
                \EntityManager::persist($essayFile);
                \EntityManager::flush();

                return new JsonResponse(['status' => 'ok', 'message' => 'ok']);
            } catch (\Exception $e) {
                \Log::error($e);
                return new JsonResponse(['status' => 'error', 'message' => 'Something went wrong with attaching file']);
            }
        }
    }

    /**
     * @return JsonResponse
     */
    public function detachFileFromEssay()
    {
        if (null !== ($account = \Auth::user()) && ($account instanceof Account)  ) {
            $essayFile = \EntityManager::getRepository(EssayFiles::class)->findOneBy([
                'essay' => \Input::get('essay_id'),
                'file' => \Input::get('file_id'),
                'scholarship' => \Input::get('scholarship_id')
            ]);

            try {
                \EntityManager::remove($essayFile);
                \EntityManager::flush();

                return new JsonResponse(['status' => 'ok', 'message' => 'ok']);
            } catch (\Exception $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'message' => 'Something went wrong with deattaching file'
                ]);
            }
        }
    }

    /**
     * @return \App\User|null
     */
    protected function getCurrentAccount(){
         return \Auth::user();
    }
}
