<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\Application;
use App\Events\ApplicationWinnerFormFilledEvent;
use App\Http\Requests\RestRequest;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestAction;
use Pz\Doctrine\Rest\RestResponse;
use Pz\Doctrine\Rest\Traits\CanHydrate;
use Pz\Doctrine\Rest\Traits\CanValidate;

class ApplicationRelatedWinnerUpdateAction extends RestAction
{
    use CanHydrate;
    use CanValidate;

    /**
     * @param RestRequest $request
     *
     * @return RestResponse
     * @throws RestException
     */
    public function handle($request)
    {
        /** @var Application $application */
        $application = $this->repository()->findById($request->getId());

        /**
         * If no winner set.
         */
        if (null === ($winner = $application->getWinner())) {
            abort(404);
        }

        $this->authorize($request, $application);
        $this->hydrateEntity($winner, $request->getData());
        $this->validateEntity($winner);
        $this->repository()->getEntityManager()->flush($winner);

        ApplicationWinnerFormFilledEvent::dispatch($winner);

        return $this->response()->resource($request,
            new Item($winner, $this->transformer(), $winner->getResourceKey())
        );
    }
}
