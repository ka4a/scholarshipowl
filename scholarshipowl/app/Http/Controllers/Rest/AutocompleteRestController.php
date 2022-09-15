<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Http\Traits\JsonResponses;
use App\Services\ZipService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Controller as BaseController;

class AutocompleteRestController extends BaseController
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * AutocompleteRestController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string    $q          Query
     *
     * @return $this
     */
    public function highschool($q)
    {
        /** @var Account $account */
        $account = \Auth::user();
        $result = [];

        if ($q = $this->stripSpecialCharacters($q)) {
            try {

                $sql = "
                    SELECT highschool_id AS id, name AS text
                    FROM highschool
                    where ".sprintf(
                        'country IN (%s) AND MATCH(name) AGAINST (:highscholl IN BOOLEAN MODE)',
                        implode(',', $this->findAutocompleteCountries($account))
                    ). " LIMIT 10";

                $stmt = $this->em->getConnection()->prepare($sql);
                $stmt->execute([':highscholl' => "*$q*"]);
                $result = $stmt->fetchAll();

            } catch (\Exception $e) {
                \Log::error($e);
                $result = [];
            }
        }

        return $this->jsonSuccessResponse($result);
    }

    /**
     * @param string  $q
     *
     * @return $this
     */
    public function college($q)
    {
        /** @var Account $account */
        $account = \Auth::user();
        $result = [];

        if ($q = $this->stripSpecialCharacters($q)) {
            try {

                $sql = "
                    SELECT college_id AS id, canonical_name AS text
                    FROM college
                    where ".sprintf(
                        'country IN (%s) AND MATCH(canonical_name) AGAINST (:college IN BOOLEAN MODE)',
                        implode(',', $this->findAutocompleteCountries($account))
                    );

                $stmt = $this->em->getConnection()->prepare($sql);
                $stmt->execute([':college' => "*$q*"]);
                $result = $stmt->fetchAll();

            } catch (\Exception $e) {
                \Log::error($e);
                $result = [];
            }
        }

        return $this->jsonSuccessResponse($result);
    }

    /**
     * @param int|string $zipCode numeric string
     * @param ZipService $zipService
     * @return \Illuminate\Http\JsonResponse
     */
	public function stateAndCity($zipCode, ZipService $zipService)
    {
        return $this->jsonSuccessResponse($zipService->getData($zipCode));
    }

    /**
     * @param Account   $account
     * @param bool      $includeCountry
     *
     * @return array|int[]
     */
    protected function findAutocompleteCountries(Account $account)
    {
        $profile = $account->getProfile();
        $countries = [];

        if ($profile->getCountry()) {
            $countries[] = $profile->getCountry()->getId();
        }

        foreach ($profile->getStudyCountries() as $studyCountry) {
            $countries[] = $studyCountry->getId();
        }

        return array_unique($countries);
    }

    /**
     * @param string $q
     *
     * @return string
     */
    protected function stripSpecialCharacters(string $q)
    {
        return trim(preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $q));
    }
}
