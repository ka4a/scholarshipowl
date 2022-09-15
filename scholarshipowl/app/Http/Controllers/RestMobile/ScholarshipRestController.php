<?php

namespace App\Http\Controllers\RestMobile;

use App\Entity\Account;
use App\Entity\AccountsFavoriteScholarships;
use App\Entity\Exception\EntityNotFound;
use App\Entity\Application;
use App\Entity\ApplicationEssayStatus;
use App\Entity\ApplicationStatus;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\Scholarship;
use App\Http\Controllers\RestController;
use App\Http\Traits\MobileResponseNormalize;
use App\Services\ApplicationService\Exception\ScholarshipNotActive;
use App\Services\ApplicationService\Exception\ScholarshipNotEligible;
use App\Services\ApplicationService;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use App\Services\ScholarshipService;
use Carbon\Carbon;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipRestController extends \App\Http\Controllers\Rest\ScholarshipRestController
{
    use MobileResponseNormalize;

    /**
     * @inheritDoc
     */
    public function eligible(Request $request)
    {
        $response = parent::eligible($request);
        $this->filterAndNormalize($response);

        return $response;
    }


    /**
     * @inheritDoc
     */
    public function sentApplication(Request $request)
    {
        $response = parent::sentApplication($request);
        $this->filterAndNormalize($response);

        return $response;
    }
}


