<?php namespace App\Auth;

use App\Entities\UserToken;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class ApiTokenGuard implements Guard
{
    use GuardHelpers;

    const HEADER = 'SUNRISE-API-Key';
    const PARAM = 'api_token';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ApiTokenGuard constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            /** @var UserToken $userToken */
            if ($userToken = $this->findByToken($token)) {
                return $userToken->getUser();
            }
        }

        return null;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        /** @var Request $request */
        $request = app('request');

        $token = $request->header(static::HEADER);

        if (empty($token)) {
            $token = $request->query(static::PARAM);
        }

        if (empty($token)) {
            $token = $request->input(static::PARAM);
        }

        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return boolval($this->findByToken($credentials[static::PARAM] ?? null));
    }

    /**
     * @param string $token
     * @return object|UserToken|null
     */
    protected function findByToken(string $token)
    {
        if (empty($token)) {
            return null;
        }
        return $this->em->getRepository(UserToken::class)->findOneBy([
            'token' => $token,
            'revoked' => null,
        ]);
    }
}