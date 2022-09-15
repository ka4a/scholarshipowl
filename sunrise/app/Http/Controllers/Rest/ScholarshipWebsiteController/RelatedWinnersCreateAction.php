<?php namespace App\Http\Controllers\Rest\ScholarshipWebsiteController;

use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipWebsite;
use App\Entities\ScholarshipWinner;
use App\Transformers\ScholarshipWinnerTransformer;
use League\Fractal\Resource\Collection;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionCreateAction;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestResponse;

class RelatedWinnersCreateAction extends RelatedCollectionCreateAction
{
    /**
     * @param RelatedWinnersCreateRequest $request
     *
     * @return RestResponse
     * @throws RestException
     */
    public function handle($request)
    {
        /** @var ScholarshipWebsite $website */
        $website = $this->repository()->findById($request->getId());
        $this->authorize($request, $website);

        $template = $this->repository()
            ->getEntityManager()
            ->getRepository(ScholarshipTemplate::class)
            ->findOneBy(['website' => $website]);

        foreach ($request->getData() as $raw) {
            $template->getPublished()[0]
                ->addWinners($winner = new ScholarshipWinner());
            $this->hydrateEntity($winner, $raw);
        }

        $this->repository()->getEntityManager()->flush();

        $winners = $this->repository()->getEntityManager()
            ->getRepository(ScholarshipWinner::class)
            ->createQueryBuilder('w')
            ->join('w.scholarship', 's')
            ->join('s.template', 't')
            ->where('t.id = :template')
            ->setParameter('template', $website->getTemplate())
            ->getQuery()
            ->getResult();

        $collection = new Collection($winners, new ScholarshipWinnerTransformer(), ScholarshipWinner::getResourceKey());
        return $this->response()->resource($request, $collection, RestResponse::HTTP_CREATED);
    }
}
