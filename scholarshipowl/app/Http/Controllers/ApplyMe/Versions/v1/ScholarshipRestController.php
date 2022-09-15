<?php namespace App\Http\Controllers\ApplyMe\Versions\v1;

use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\{
    RequirementFile, RequirementImage, RequirementText, Scholarship
};
use App\Http\Controllers\RestController;
use App\Rest\Index\LimitAndStartQueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;

/**
 *
 * DOCS
 *
 */

/**
 * @SWG\Get(
 * 		path="/scholarship/{id}",
 * 		tags={"Scholarship"},
 * 		operationId="rest::v1.scholarship.show",
 *      description="Returns specific scholarship",
 * 		summary="Fetch scholarship",
 *      produces={"application/json"},
 *      consumes={"application/x-www-form-urlencoded"},
 *
 *     @SWG\Parameter(
 *          type="string",
 *          in="header",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/JWT Token")
 *      ),
 *      @SWG\Parameter(
 *          type="object",
 *          in="session",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/Account")
 *      ),
 *     @SWG\Parameter(
 *          type="object",
 *          in="session",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/Admin")
 *      ),
 *     @SWG\Parameter(
 *          type="integer",
 *          in="formData",
 *          name="id",
 *          required=true,
 *      ),
 *     @SWG\Response(
 *         response=200,
 *         description="Scholarship information."
 *     ),
 *     @SWG\Response(
 *         response=401,
 *         description="Unauthorized.",
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="Not found.",
 *     ),
 *  )
 *
 */

/**
 * @SWG\Delete(
 * 		path="/scholarship/{id}",
 * 		tags={"Scholarship"},
 * 		operationId="rest::v1.scholarship.destroy",
 *      description="Delete scholarship",
 * 		summary="Delete scholarship",
 *      produces={"application/json"},
 *      consumes={"application/x-www-form-urlencoded"},
 *
 *     @SWG\Parameter(
 *          type="string",
 *          in="header",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/JWT Token")
 *      ),
 *     @SWG\Parameter(
 *          type="integer",
 *          in="path",
 *          name="id",
 *          required=true
 *      ),
 *      @SWG\Parameter(
 *          type="object",
 *          in="session",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/Account")
 *      ),
 *     @SWG\Parameter(
 *          type="object",
 *          in="session",
 *          name="Authorization",
 *          required=true,
 *          @SWG\Schema(ref="#/definitions/Admin")
 *      ),
 *     @SWG\Response(
 *         response=200
 *     ),
 *     @SWG\Response(
 *         response=401,
 *         description="Unauthorized.",
 *     ),
 *     @SWG\Response(
 *         response=404,
 *         description="Not Found.",
 *     ),
 *  )
 *
 */
class ScholarshipRestController extends RestController
{
    const PARAM_ELIGIBLE_ACCOUNT = 'accountId';

    /**
     * @return ScholarshipRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(Scholarship::class);
    }

    /**
     * @return ScholarshipResource
     */
    protected function getResource()
    {
        return new ScholarshipResource();
    }

    /**
     * @param Request $request
     *
     * @return QueryBuilder
     */
    protected function getBaseIndexQuery(Request $request)
    {
        $queryBuilder = $this->getRepository()->createQueryBuilder('s');
        return $queryBuilder;

    }

    /**
     * @param Request $request
     *
     * @return QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(s.scholarshipId)');
    }


    /**
     * @SWG\Get(
     * 		path="/scholarship/eligible/{accountId?}",
     * 		tags={"Scholarship"},
     * 		operationId="rest::v1.scholarship.eligible",
     *      description="Returns all eligible scholarship",
     * 		summary="Fetch all eligible scholarships",
     *      produces={"application/json"},
     *      consumes={"application/x-www-form-urlencoded"},
     *
     *     @SWG\Parameter(
     *          type="string",
     *          in="header",
     *          name="Authorization",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/JWT Token")
     *      ),
     *      @SWG\Parameter(
     *          type="object",
     *          in="session",
     *          name="Authorization",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Account")
     *      ),
     *     @SWG\Parameter(
     *          type="object",
     *          in="session",
     *          name="Authorization",
     *          required=true,
     *          @SWG\Schema(ref="#/definitions/Admin")
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Scholarships information."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized.",
     *     ),
     *  )
     *
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eligible(Request $request)
    {
        $account = $this->validateAccount($request);

        $this->authorize([Scholarship::class, $account]);

        $eligibleScholarships = $this->getRepository()->findEligibleNotAppliedScholarshipsIds($account);
        $eligibilityQueryBuilder = function(QueryBuilder $queryBuilder) use ($eligibleScholarships) {
            return $queryBuilder
                ->andWhere('s.scholarshipId IN (:eligibleScholarshipsIds)')
                ->setParameter('eligibleScholarshipsIds', $eligibleScholarships);
        };

        $baseQueryBuilder = $this->getBaseIndexQuery($request);
        $baseQueryBuilder = ScholarshipRepository::withApplications($baseQueryBuilder, $account);
        $baseQueryBuilder = ScholarshipRepository::withApplicationRequirements($baseQueryBuilder, $account);
        $baseQueryBuilder
            ->leftJoin(RequirementText::class, 'RT', 'WITH', 'RT.scholarship = s.scholarshipId')
            ->leftJoin(RequirementFile::class, 'RF', 'WITH', 'RF.scholarship = s.scholarshipId')
            ->leftJoin(RequirementImage::class, 'RI', 'WITH', 'RI.scholarship = s.scholarshipId')
            ->where('RT.requirementName = 1 AND RF.id IS NULL AND RI.id IS NULL');

        $dataQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->add(new LimitAndStartQueryBuilder($request))
            ->add($eligibilityQueryBuilder)
            ->process($baseQueryBuilder);

        $baseCountQueryBuilder = clone $baseQueryBuilder;
        $baseCountQueryBuilder->select('COUNT(s.scholarshipId)');

        $countQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->add($eligibilityQueryBuilder)
            ->process($baseCountQueryBuilder);

        return $this->jsonIndexResponse($dataQuery, $countQuery, new ScholarshipResource($account));
    }
}


