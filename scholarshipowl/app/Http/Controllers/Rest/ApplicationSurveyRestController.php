<?php namespace App\Http\Controllers\Rest;

use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;
use App\Entity\Repository\EntityRepository;
use App\Entity\RequirementSurvey;
use App\Entity\Resource\ApplicationSurveyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Doctrine\ORM\EntityManager;
use App\Services\ApplicationService;

class ApplicationSurveyRestController extends ApplicationRequirementAbstractController
{
    /**
     * @var ApplicationSurveyResource
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
     * ApplicationSurveyRestController constructor.
     * @param EntityManager $em
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
        return \EntityManager::getRepository(ApplicationSurvey::class);
    }

    /**
     * @return ApplicationSurveyResource
     */
    public function getResource()
    {
        if ($this->resource === null) {
            $this->resource = new ApplicationSurveyResource();
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
        $account = $this->validateAccount($request);

        $this->validate($request, ['requirementId' => 'required|entity:RequirementSurvey']);

        /** @var RequirementSurvey $requirementSurvey */
        $requirementSurvey = $this->findById($request->get('requirementId'), RequirementSurvey::class);

        /** @var ApplicationSurvey $applicationSurvey */
        $applicationSurvey = $this->getRepository()->findOneBy([
            'requirement' => $requirementSurvey,
            'account' => $account
        ]);

        $this->validate($request, [
            'survey' => 'required|array',
        ]);

        $answers = $request->get('survey', []);

        if (!empty($answers)) {
            $survey  = $requirementSurvey->getSurveyWithId();
            $indexed = [];
            foreach ($survey as $i) {
                $indexed[$i['id']] = $i;
            }

            $hasErrors = false;
            $answersToStore = [];
            foreach ($answers as $questionId => $selectedOptions) {
                $requirement = $indexed[$questionId] ?? null;
                if (!$requirement) {
                    $hasErrors = true;
                    break;
                }

                $verifiedSelectedOptions = array_intersect($selectedOptions, array_keys($requirement['options']));

                if (count($selectedOptions) !== count($verifiedSelectedOptions)) {
                    $hasErrors = true;
                    break;
                }

                // radio must have only one option selected
                if ($requirement['type'] === 'radio') {
                    $verifiedSelectedOptions = [reset($verifiedSelectedOptions)];
                }

                $answersToStore[] = [
                    'type' => $requirement['type'],
                    'options' => $verifiedSelectedOptions,
                    'question' => $requirement['question']
                ];
            }

            if ($hasErrors) {
                return $this->jsonErrorResponse(
                    ['survey' => ['Survey answers must have at least one option selected from the provided options list']],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }

            if (count($answers) !== count($survey)) {
                return $this->jsonErrorResponse(
                    ['survey' => ['Survey must have all questions answered']],
                    JsonResponse::HTTP_BAD_REQUEST
                );
            }

            if ($applicationSurvey) {
                $applicationSurvey->setAnswers($answersToStore);
            } else {
                $applicationSurvey = new ApplicationSurvey($requirementSurvey, $answersToStore, $account);
                $this->em->persist($applicationSurvey);
            }

            $this->em->flush();
        } else {
            return $this->jsonErrorResponse(
                ['survey' => ['No answers found']],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }


        return $this->jsonResponse($applicationSurvey);
    }
}
