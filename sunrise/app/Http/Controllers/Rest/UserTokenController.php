<?php

/**
 * Auto-generated controller file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest;

use App\Entities\UserToken;
use App\Http\Controllers\Rest\UserTokenController\CreateAction;
use App\Http\Controllers\Rest\UserTokenController\CreateRequest;
use App\Http\Controllers\Rest\UserTokenController\UpdateAction;
use App\Http\Controllers\Rest\UserTokenController\UpdateRequest;
use App\Http\Requests\RestRequest;
use App\Transformers\UserTokenTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Controller;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\DeleteAction;

class UserTokenController extends Controller
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
	 * Attribute that will be used for search query. Example: like "%prop%"
	 * @return string|null
	 */
	public function getFilterProperty()
	{
		return null;
	}

	/**
	 * List of attributes that allowed for filtering
	 * @return array
	 */
	public function getFilterable()
	{
		return [];
	}

	/**
	 * @param CreateRequest $request
	 * @return RestResponse
	 */
	public function create(CreateRequest $request)
	{
		return (
			new CreateAction(
				$this->em->getRepository(UserToken::class),
				new UserTokenTransformer()
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
				$this->em->getRepository(UserToken::class),
				new UserTokenTransformer()
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
				$this->em->getRepository(UserToken::class),
				new UserTokenTransformer()
			)
		)->dispatch($request);
	}
}
