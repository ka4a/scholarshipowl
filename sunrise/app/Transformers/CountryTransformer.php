<?php

/**
 * Auto-generated transformer file
 */

declare(strict_types=1);

namespace App\Transformers;

use App\Entities\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{
	/**
	 * @param Country $country
	 * @return array
	 */
	public function transform(Country $country)
	{
		return [
			'id' => $country->getId(),
            'name' => $country->getName(),
            'abbreviation' => $country->getAbbreviation(),
		];
	}
}
