<?php namespace App\Http\Controllers\Rest;

use App\Entities\Organisation;
use App\Entities\Role;
use App\Entities\Scholarship;
use App\Entities\User;
use App\Entities\UserToken;
use App\Entities\UserTutorial;
use App\Http\Controllers\Rest\UserController\RelatedScholarships;
use App\Http\Controllers\Rest\UserController\UserUpdateRequest;
use App\Http\Controllers\RestController;
use App\Http\Requests\RestRequest;
use App\Repositories\ScholarshipRepository;
use App\Transformers\UserTokenTransformer;
use App\Transformers\UserTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponseFactory;
use League\Fractal\Resource\Item;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class UserRestController extends RestController
{
    use ValidatesRequests;
    use AuthorizesRequests;

    /**
     * UserRestController constructor.
     *
     * @param EntityManager      $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, User::class);
        $this->transformer = new UserTransformer();
    }

    /**
     * Return authenticated user.
     *
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function me(RestRequest $request)
    {
        $user = $request->user();

//        $include = [
//            UserTransformer::INCLUDE_ROLES,
//            UserTransformer::INCLUDE_ORGANISATIONS,
//            UserTransformer::INCLUDE_ORGANISATIONS.'.owners',
//            UserTransformer::INCLUDE_TUTORIALS,
//        ];
//
//        $fields = [
//            User::getResourceKey() => [
//                'name',
//                'email',
//                'picture',
//                UserTransformer::INCLUDE_ROLES,
//                UserTransformer::INCLUDE_ORGANISATIONS,
//                UserTransformer::INCLUDE_TUTORIALS,
//            ],
//            Organisation::getResourceKey() => [
//                'name',
//            ],
//        ];

        // if ($user->isRoot()) {
        //     $fields[Organisation::getResourceKey()][] = 'owners';
        // }

        // $request->query->set('include', implode(',', $include));
        // $request->query->set('fields', array_map(
        //     function(array $fieldSet) {
        //         return implode(',', $fieldSet);
        //     },
        //     $fields
        // ));

        $responseFactory = new RestResponseFactory();
        return $responseFactory->resource($request,
            new Item($user, $this->transformer(), $user->getResourceKey())
        );
    }

    /**
     * @param UserUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function updateUser(UserUpdateRequest $request)
    {
        return (new UpdateAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * Show scholarships that user have access to.
     *
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedScholarships(RestRequest $request)
    {
        /** @var ScholarshipRepository $related */
        $related = $this->repository()->getEntityManager()->getRepository(Scholarship::class);
        return (new RelatedScholarships($this->repository(), $related))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedTokens(RestRequest $request)
    {
        return (new RelatedCollectionAction(
            $this->repository(),
            'user',
            $this->repository()->getEntityManager()->getRepository(UserToken::class),
            new UserTokenTransformer()
        ))
            ->dispatch($request);
    }
}
