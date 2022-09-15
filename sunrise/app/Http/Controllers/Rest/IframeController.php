<?php

/**
 * Auto-generated controller file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest;

use App\Entities\Iframe;
use App\Http\Controllers\Rest\IframeController\CreateAction;
use App\Http\Controllers\Rest\IframeController\CreateRequest;
use App\Http\Controllers\Rest\IframeController\UpdateAction;
use App\Http\Controllers\Rest\IframeController\UpdateRequest;
use App\Http\Requests\RestRequest;
use App\Transformers\IframeTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Controller;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\DeleteAction;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;

class IframeController extends Controller
{
	/** @var EntityManager */
	protected $em;


	/**
	 * Create rest controller
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param RestRequest $request
	 * @return RestResponse
	 */
	public function show(RestRequest $request)
	{
		return (
			new ShowAction(
				$this->em->getRepository(Iframe::class),
				new IframeTransformer()
			)
		)->dispatch($request);
	}


	/**
	 * @param CreateRequest $request
	 * @return RestResponse
	 */
	public function create(CreateRequest $request)
	{
		return (
			new CreateAction(
				$this->em->getRepository(Iframe::class),
				new IframeTransformer()
			)
		)->dispatch($request);
	}


	/**
	 * @param UpdateRequest $request
	 * @return RestResponse
	 */
	public function update(UpdateRequest $request)
	{
		return (
			new UpdateAction(
				$this->em->getRepository(Iframe::class),
				new IframeTransformer()
			)
		)->dispatch($request);
	}


	/**
	 * @param RestRequest $request
	 * @return RestResponse
	 */
	public function delete(RestRequest $request)
	{
		return (
			new DeleteAction(
				$this->em->getRepository(Iframe::class),
				new IframeTransformer()
			)
		)->dispatch($request);
	}
}
