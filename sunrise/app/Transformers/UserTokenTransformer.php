<?php

/**
 * Auto-generated transformer file
 */

declare(strict_types=1);

namespace App\Transformers;

use App\Entities\User;
use App\Entities\UserToken;
use League\Fractal\TransformerAbstract;

class UserTokenTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['user'];

	/**
	 * @param UserToken $userToken
	 * @return array
	 */
	public function transform(UserToken $userToken)
	{
		return [
			'id' => $userToken->getId(),
            'name' => $userToken->getName(),
            'token' => $userToken->getToken(),
            'revoked' => $userToken->getRevoked() ? $userToken->getRevoked()->format('c') : null,
            'createdAt' => $userToken->getCreatedAt()->format('c'),
            'updatedAt' => $userToken->getUpdatedAt()->format('c'),
		];
	}

    /**
     * @param UserToken $token
     * @return \League\Fractal\Resource\Item
     */
	public function includeUser(UserToken $token)
    {
        return $this->item($token->getUser(), new UserTransformer(), User::getResourceKey());
    }
}
