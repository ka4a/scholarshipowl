<?php

/**
 * Auto-generated controller file
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest;

use App\Entities\Country;
use App\Http\Requests\RestRequest;
use App\Transformers\CountryTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Controller;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\IndexAction;

class CountryController extends Controller
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
	 * @param RestRequest $request
	 * @return RestResponse
	 */
	public function index(RestRequest $request)
	{
		return (
			new IndexAction(
				$this->em->getRepository(Country::class),
				new CountryTransformer()
			)
		)
			->setFilterProperty($this->getFilterProperty())
			->setFilterable($this->getFilterable())
			->dispatch($request);
	}
}
