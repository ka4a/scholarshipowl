<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\ApplicationImage;
use App\Entity\Repository\EntityRepository;
use App\Entity\RequirementImage;
use App\Entity\Resource\ApplicationImageResource;
use App\Services\ApplicationService;
use App\Traits\base64file;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class ApplicationImageRestController extends ApplicationRequirementAbstractController
{
    use base64file;

    /**
     * @var ApplicationImageResource
     */
    protected $resource;

    /**
     * @var ApplicationService
     */

    protected $as;

    /**
     * ApplicationImageRestController constructor.
     *
     * @param EntityManager      $em
     * @param ApplicationService $service
     */
    public function __construct(EntityManager $em, ApplicationService $service)
    {
        parent::__construct($em);

        $this->as = $service;
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(ApplicationImage::class);
    }

    /**
     * @return ApplicationImageResource
     */
    protected function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationImageResource();
        }

        return $this->resource;
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('ai');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(ai.id)');
    }

    public function index(Request $request)
    {
        $this->getResource()->setFullScholarship(false);

        return parent::index($request);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $account = $this->validateAccount($request);
        $this->authorize('store', $this->getRepository()->getClassName());
        $this->validate($request, ['requirementImageId' => 'required|entity:RequirementImage']);

        /** @var RequirementImage $requirementImage */
        $requirementImage = $this->findById($request->get('requirementImageId'), RequirementImage::class);

        $criteria = ['requirement' => $requirementImage, 'account' => $account];
        if ($applicationImage = $this->getRepository()->findOneBy($criteria)) {
            return $this->update($request, $applicationImage->getId());
        }

        $this->validate($request, [
            'accountFileId'     => 'required_without_all:file,fileBase64|entity:AccountFile',
            'file'              => 'required_without_all:accountFileId,fileBase64|file',
            'fileBase64'        => 'required_without_all:accountFileId,file|string',
        ]);

        if ($base64 = $request->get('fileBase64')) {
            $file = $this->makeFileFromBase64string($base64);
        }

        $accountFile = $this->validateOrCreateAccountFile(
            $account,
            $requirementImage,
            isset($file) ? $file : $request->file('file'),
            $request->get('accountFileId')
        );

        if (file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        $fromCamera = $request->get('fromCamera', false);
        $applicationImage = new ApplicationImage($accountFile, $requirementImage, $fromCamera);
        $this->em->persist($applicationImage);
        $this->em->flush();

        return $this->jsonResponse($applicationImage);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var ApplicationImage $applicationImage */
        $this->authorize('update', $applicationImage = $this->findById($id));
        $this->validate($request, [
            'accountFileId'     => 'required_without_all:file,fileBase64|entity:AccountFile',
            'file'              => 'required_without_all:accountFileId,fileBase64|file',
            'fileBase64'        => 'required_without_all:accountFileId,file|string',
        ]);

        if ($accountFile = $this->validateOrCreateAccountFile(
            $applicationImage->getAccount(),
            $applicationImage->getRequirement(),
            isset($file) ? $file : $request->file('file'),
            $request->get('accountFileId')
        )) {
            if($request->has('fromCamera')){
                $applicationImage->setFromCamera($request->get('fromCamera'));
            }
            $applicationImage->setAccountFile($accountFile);
            $this->em->flush();
        }

        if (file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        return $this->jsonResponse($applicationImage);
    }

    /**
     * @param Account           $account
     * @param RequirementImage  $requirementImage
     * @param UploadedFile|null $file
     * @param null              $accountFileId
     *
     * @return null|AccountFile
     */
    protected function validateOrCreateAccountFile(
        Account $account,
        RequirementImage $requirementImage,
        File $file = null,
        $accountFileId = null
    ) {
        $accountFile = null;

        if ($accountFileId) {

            /** @var AccountFile $accountFile */
            $accountFile = $this->findById($accountFileId, AccountFile::class);

            $this->authorizeForUser($account, 'show', $accountFile);
            $this->validateWith($this->as->validatorRequirementImage($accountFile->getFileAsTemporary(), $requirementImage));

        } else if ($file) {

            $this->validateWith($this->as->validatorRequirementImage($file, $requirementImage));
            $accountFile = new AccountFile($file, $account);
            $this->em->persist($accountFile);

        }

        return $accountFile;
    }
}
