<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\ApplicationInput;
use App\Entity\Repository\EntityRepository;
use App\Entity\RequirementInput;
use App\Entity\Resource\ApplicationInputResource;
use App\Http\Controllers\RestController;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ApplicationInputRestController extends ApplicationRequirementAbstractController
{
    /**
     * @var ApplicationInputResource
     */
    protected $resource;

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(ApplicationInput::class);
    }

    /**
     * @return ApplicationInputResource
     */
    protected function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationInputResource();
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
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function store(Request $request)
    {

        $account = $this->validateAccount($request);
        $this->authorize('store', $this->getRepository()->getClassName());
        $this->validate($request, ['requirementInputId' => 'required|exists:App\Entity\RequirementInput,id']);
        /** @var RequirementInput $requirementInput */
        $requirementInput = $this->em->find(RequirementInput::class, $request->get('requirementInputId'));
        if ($requirementInput->getRequirementName()->getName() === 'Input') {
             $this->validate($request, ['text' => 'required|string'], ['Please enter a text.']);
        } else {
            $this->validate($request, ['text' => 'required|url']);
        }

        /** @var ApplicationInput $applicationInput */
        $criteria = ['requirement' => $requirementInput, 'account' => $account];
        if ($applicationInput = $this->getRepository()->findOneBy($criteria)) {
            $applicationInput->setText($request->get('text'));
        }

        if (!$applicationInput) {
            $applicationInput = new ApplicationInput($requirementInput, $account, $request->get('text'));
            $this->em->persist($applicationInput);
        }

        $this->em->flush();

        return $this->jsonResponse($applicationInput);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        /** @var ApplicationInput $applicationInput */
        $this->authorize($applicationInput = $this->findById($id));
        $this->validate($request, ['text' => 'required|url']);

        $applicationInput->setText($request->get("text"));

        return $this->jsonResponse($applicationInput);
    }
}
