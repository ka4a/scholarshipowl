<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\ApplicationFile;
use App\Entity\RequirementFile;
use App\Entity\Resource\ApplicationFileResource;
use App\Http\Controllers\RestController;
use App\Services\ApplicationService;
use App\Traits\base64file;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;

class ApplicationFileRestController extends ApplicationRequirementAbstractController
{
    use base64file;

    /**
     * @var ApplicationFileResource
     */
    private $resource;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplicationFileRestController constructor.
     *
*@param EntityManager            $em
     * @param ApplicationService $applicationService
     */
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
        parent::__construct($em);
        $this->applicationService = $applicationService;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(ApplicationFile::class);
    }

    /**
     * @return ApplicationFileResource
     */
    protected function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationFileResource();
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
        return $this->getRepository()->createQueryBuilder('af');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(af.id)');
    }

    /**
     * @inheritdoc
     */
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
        $this->validate($request, ['requirementFileId' => 'required|entity:RequirementFile']);

        /** @var RequirementFile $requirementFile */
        $requirementFile = $this->findById($request->get('requirementFileId'), RequirementFile::class);
        $criteria = ['requirement' => $requirementFile, 'account' => $account];
        if ($applicationFile = $this->getRepository()->findOneBy($criteria)) {
            return $this->update($request, $applicationFile->getId());
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
            $requirementFile,
            isset($file) ? $file : $request->file('file'),
            $request->get('accountFileId')
        );

        if (file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        $applicationFile = new ApplicationFile($accountFile, $requirementFile);
        $this->em->persist($applicationFile);
        $this->em->flush();

        return $this->jsonResponse($applicationFile);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var ApplicationFile $applicationFile */
        $this->authorize('update', $applicationFile = $this->findById($id));
        $this->validate($request, [
            'accountFileId'     => 'required_without_all:file,fileBase64|entity:AccountFile',
            'file'              => 'required_without_all:accountFileId,fileBase64|file',
            'fileBase64'        => 'required_without_all:accountFileId,file|string',
        ]);

        if ($base64 = $request->get('fileBase64')) {
            $file = $this->makeFileFromBase64string($base64);
        }

        if ($accountFile = $this->validateOrCreateAccountFile(
                $applicationFile->getAccount(),
                $applicationFile->getRequirement(),
                isset($file) ? $file : $request->file('file'),
                $request->get('accountFileId')
        )) {
            $applicationFile->setAccountFile($accountFile);
            $this->em->flush();
        }

        if (file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        return $this->jsonResponse($applicationFile);
    }

    /**
     * @param Account           $account
     * @param RequirementFile   $requirementFile
     * @param UploadedFile|null $file
     * @param null              $accountFileId
     *
     * @return AccountFile|null
     */
    protected function validateOrCreateAccountFile(
        Account $account,
        RequirementFile $requirementFile,
        File $file = null,
        $accountFileId = null
    )
    {
        $accountFile = null;

        if ($accountFileId) {

            /** @var AccountFile $accountFile */
            $accountFile = $this->findById($accountFileId, AccountFile::class);

            $this->authorizeForUser($account, 'show', $accountFile);
            $this->validateWith($this->applicationService->validatorRequirementFile($accountFile->getFileAsTemporary(), $requirementFile));

        } else if ($file) {

            $this->validateWith($this->applicationService->validatorRequirementFile($file, $requirementFile));

            $accountFile = new AccountFile($file, $account);
            $this->em->persist($accountFile);

        }

        return $accountFile;
    }
}
