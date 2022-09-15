<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\ApplicationText;
use App\Entity\Repository\EntityRepository;
use App\Entity\RequirementText;
use App\Entity\Resource\ApplicationTextResource;
use App\Http\Exceptions\JsonErrorException;
use App\Traits\base64file;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManager;
use App\Services\ApplicationService;

class ApplicationTextRestController extends ApplicationRequirementAbstractController
{
    use base64file;

    /**
     * @var ApplicationTextResource
     */
    protected $resource;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ApplicationService
     */
    protected $applicationService;

    /**
     * ApplicationFileRestController constructor.
     *
     * @param EntityManager            $em
     * @param ApplicationService $applicationService
     */
    public function __construct(EntityManager $em, ApplicationService $applicationService)
    {
        parent::__construct($em);
        $this->applicationService = $applicationService;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return \EntityManager::getRepository(ApplicationText::class);
    }

    /**
     * @return ApplicationTextResource
     */
    public function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationTextResource();
        }

        return $this->resource;
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('at');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(at.id)');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
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
        $this->authorize('store', ApplicationText::class);
        $account = $this->validateAccount($request);

        $this->validate($request, ['requirementTextId' => 'required|entity:RequirementText']);

        /** @var RequirementText $requirementText */
        $requirementText = $this->findById($request->get('requirementTextId'), RequirementText::class);
        $criteria = ['requirement' => $requirementText, 'account' => $account];
        if ($applicationText = $this->getRepository()->findOneBy($criteria)) {
            return $this->update($request, $applicationText);
        }

            $this->validate($request, [
                'accountFileId'     => 'sometimes|required_without_all:file,text,fileBase64|entity:AccountFile',
                'file'              => 'sometimes|required_without_all:accountFileId,text,fileBase64|file',
                'fileBase64'        => 'sometimes|required_without_all:accountFileId,file,text|string',
                'text'              => 'sometimes|required|string|',
            ]);

        if ($base64 = $request->get('fileBase64')) {
            $file = $this->makeFileFromBase64string($base64);
        }

        if ($request->has('text')) {
            $applicationText = new ApplicationText($requirementText, null, $request->get('text'), $account);
        } else {
            $this->validateAllowFile($requirementText);
            $applicationText = new ApplicationText($requirementText, $this->validateOrCreateAccountFile(
                $account,
                $requirementText,
                isset($file) ? $file : $request->file('file'),
                $request->get('accountFileId')
            ));
        }

        if (file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        $this->em->persist($applicationText);
        $this->em->flush();

        return $this->jsonResponse($applicationText);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var ApplicationText $applicationText */
        $this->authorize('update', $applicationText = $this->findById($id));
        $this->validate($request, [
            'accountFileId'     => 'sometimes|required_without_all:file,text,fileBase64|entity:AccountFile',
            'file'              => 'sometimes|required_without_all:accountFileId,text,fileBase64|file',
            'fileBase64'        => 'sometimes|required_without_all:accountFileId,file,text|string',
            'text'              => 'sometimes|required|string|',
        ]);

        if ($base64 = $request->get('fileBase64')) {
            $file = $this->makeFileFromBase64string($base64);
        }

        if ($text = $request->get('text')) {
            $applicationText->setText($text);
        } else {
            $this->validateAllowFile($applicationText->getRequirement());

            $oldAccountFile = $applicationText->getAccountFile();
            $applicationText->setAccountFile($this->validateOrCreateAccountFile(
                $applicationText->getAccount(),
                $applicationText->getRequirement(),
                isset($file) ? $file : $request->file('file'),
                $request->get('accountFileId')
            ));
            $this->em->flush();
        }

        if (isset($this->base64tempFile) && file_exists($this->base64tempFile)) {
            unlink($this->base64tempFile);
        }

        if(isset($oldAccountFile) && !is_null($oldAccountFile)){
            $this->em->remove($oldAccountFile);
        }
        $this->em->flush();

        return $this->jsonResponse($applicationText);
    }

    /**
     * @param Account           $account
     * @param RequirementText   $requirementText
     * @param UploadedFile|null $file
     * @param int|null          $accountFileId
     *
     * @return AccountFile|null
     */
    protected function validateOrCreateAccountFile(
        Account $account,
        RequirementText $requirementText,
        \Symfony\Component\HttpFoundation\File\File $file = null,
        int $accountFileId = null
    )
    {
        $accountFile = null;

        if ($accountFileId) {

            /** @var AccountFile $accountFile */
            $accountFile = $this->findById($accountFileId, AccountFile::class);

            $this->authorizeForUser($account, 'show', $accountFile);
            $this->validateWith($this->applicationService->validatorRequirementFile($accountFile->getFileAsTemporary(), $requirementText));

        } else if ($file) {

            $this->validateWith($this->applicationService->validatorRequirementFile($file, $requirementText));

            $accountFile = new AccountFile($file, $account, null, $requirementText->getRequirementName()->getId());
            $this->em->persist($accountFile);

        }

        return $accountFile;
    }

    /**
     * @param RequirementText $requirementText
     *
     * @throws JsonErrorException
     */
    protected function validateAllowFile(RequirementText $requirementText)
    {
        if (!$requirementText->getAllowFile()) {
            throw new JsonErrorException(
                sprintf('Requirement text (%d) not allowing uploading files!', $requirementText->getId()),
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
    }
}
