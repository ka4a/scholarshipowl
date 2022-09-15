<?php namespace Tests;

use App\Doctrine\Types\RecurrenceConfigType\WeeklyConfig;
use App\Entities\Application;
use App\Entities\Field;
use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\Passport\OauthClient;
use App\Entities\Requirement;
use App\Entities\Role;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateRequirement;
use App\Entities\ScholarshipWebsite;
use App\Entities\ScholarshipWinner;
use App\Entities\ApplicationWinner;
use App\Entities\State;
use App\Entities\User;
use App\Entities\UserToken;
use App\Providers\AuthServiceProvider;
use App\Services\ApplicationService;
use App\Services\GoogleVision;
use App\Services\MauticService;
use App\Services\ScholarshipManager;
use App\Services\UserManager;

use Doctrine\ORM\EntityManager;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Mautic\Api\Contacts;
use Mautic\Api\Emails;
use Mautic\Auth\OAuth;
use App\Services\MauticService\Api\Smses;
use Mockery\MockInterface;
use Pz\Doctrine\Rest\RestRepository;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassHydrator;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;
use GuzzleHttp\Psr7\Response as Psr7Response;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Default headers for JSON API requests.
     *
     * @var array
     */
    protected $defaultJsonApiHeaders = [
        'X-Requested-With' => 'XMLHttpRequest',
    ];

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $manager;

    /**
     * @var RestRepository
     */
    protected $scholarships;

    protected $client;

    /**
     * @var MauticService
     */
    protected $mauticService;

    /**
     * @var OAuth
     */
    protected $mauticOAuth;

    /**
     * @var Contacts|MockInterface
     */
    protected $mauticContacts;

    /**
     * @var Emails|MockInterface
     */
    protected $mauticEmails;

    /**
     * @var Smses|MockInterface
     */
    protected $mauticSmses;

    /**
     * @var PubSubClient|MockInterface
     */
    protected $pubsubClient;

    /**
     * @var GoogleVision|MockInterface
     */
    protected $googleVision;

    /**
     * @param string $name
     * @return OauthClient
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOAuthClient($name = 'test client')
    {
        $criteria = ['name' => $name];
        if (null === ($this->client = $this->em()->getRepository(OauthClient::class)->findOneBy($criteria))) {
            $this->client = new OauthClient();
            $this->client->setName('test client');
            $this->client->setRedirect(route('index'));
            $this->em()->persist($this->client);
            $this->em()->flush($this->client);
        }

        return $this->client;
    }

    /**
     * @param string $scope
     * @param OauthClient|null $client
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getOAuthClientHeaders($scope = '*', OauthClient $client = null)
    {
        if ($client === null) {
            $client = $this->getOAuthClient();
        }

        $response = $this->json('POST', '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $client->getId(),
            'client_secret' => $client->getSecret(),
            'scope' => $scope,
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'expires_in', 'token_type']);

        $response = json_decode($response->getContent(), true);
        return ['Authorization' => $response['token_type'].' '.$response['access_token']];
    }

    /**
     * @return EntityManager
     */
    public function em()
    {
        return $this->em;
    }

    /**
     * @return ScholarshipManager
     */
    public function sm()
    {
        return $this->manager;
    }

    /**
     * @return UserManager
     */
    public function um()
    {
        return app(UserManager::class);
    }

    /**
     * @return ApplicationService
     */
    public function applicationService()
    {
        return app(ApplicationService::class);
    }

    /**
     * Run before each test.
     */
    public function setUp()
    {
        parent::setUp();
        AuthServiceProvider::$clientAuth = null;
        $this->em = app(EntityManager::class);
        $this->manager = app(ScholarshipManager::class);
        $this->scholarships = $this->em->getRepository(Scholarship::class);

        $this->getOAuthClient();
        $this->em()->beginTransaction();

        $this->mauticOAuth = \Mockery::mock(OAuth::class);
        $this->app->instance(OAuth::class, $this->mauticService);

        $this->mauticContacts = \Mockery::mock(Contacts::class);
        $this->mauticEmails = \Mockery::mock(Emails::class);
        $this->mauticSmses = \Mockery::mock(Smses::class);

        $this->mauticService = \Mockery::mock(new MauticService(
            $this->app->make('em'),
            $this->mauticContacts,
            $this->mauticEmails,
            $this->mauticSmses
        ));

        $this->app->instance(MauticService::class, $this->mauticService);

        $this->pubsubClient = \Mockery::mock(PubSubClient::class);

        $testTopic = \Mockery::mock(Topic::class);
        $testTopic->shouldReceive('publish');
        $this->pubsubClient->shouldReceive('topic')
            ->andReturn($testTopic);

        $this->googleVision = \Mockery::mock(GoogleVision::class);
        $this->googleVision->shouldReceive('findWinnerFace')
            ->andReturnUsing(function(ApplicationWinner $winner) {
                return \Image::make(File::image(
                    $winner->getPhoto()->getName(),
                    ScholarshipWinner::PHOTO_SIZE,
                    ScholarshipWinner::PHOTO_SIZE
                ));
            });

        $this->app->instance(PubSubClient::class, $this->pubsubClient);
        $this->app->instance(GoogleVision::class, $this->googleVision);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->em()->rollback();
        $this->em()->close();
        $this->em = null;
    }

    /**
     * Flush all entity managers.
     */
    protected function flushEms()
    {
        $this->em()->flush();
    }

    /**
     * @param int $num
     * @param bool $root
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function generateUser($num = 1, $root = true)
    {
        $user = new User();
        $user->setName('test');
        $user->setEmail("test@test$num.com");
        $user->setPassword(Hash::make(str_random()));
        if ($root) {
            /** @var Role $root */
            $root = $this->em->find(Role::class, Role::ROOT);
            $user->addRoles($root);
        }
        $this->em()->persist($user);
        $this->em()->flush($user);
        return $user;
    }

    /**
     * @param User $user
     * @param string $name
     * @return UserToken
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateUserToken(User $user, $name = 'Test token')
    {
        $token = new UserToken();
        $token->setUser($user);
        $token->setToken(str_random('40'));
        $token->setName($name);
        $this->em()->persist($token);
        $this->em()->flush($token);
        return $token;
    }

    /**
     * @param string $email
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function registerUser($email = 'test@unit_test.com')
    {
        return $this->um()->registration($email, str_random());
    }

    /**
     * @param string $name
     * @param User|null $user
     * @return Organisation
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function generateOrganisation($name = 'Test organisation', User $user = null)
    {
        $organisation = new Organisation();
        $organisation->setName($name);

        $this->em()->persist($organisation);
        $this->em()->flush($organisation);

        if ($user) {
            $user->addOrganisationRoles(
                $organisation->getRoles()
                    ->filter(function(OrganisationRole $role) { return $role->isOwner(); })
                    ->first()
            );

            $this->em()->flush($user);
        }

        return $organisation;
    }

    /**
     * @param Scholarship $scholarship
     * @param string $email
     * @param string $source
     * @param array $data
     * @return Application
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function generateApplication(Scholarship $scholarship, $email, $data = [], $source = Application::SOURCE_NONE)
    {
        $name = 'Test name';
        $phone = '1234567890';

        return $this->applicationService()
            ->apply($scholarship, $data + [
                Field::NAME => $name,
                Field::PHONE => $phone,
                Field::STATE => State::STATE_ALABAMA,
                Field::EMAIL => $email,
                ApplicationService::APPLICATION_SOURCE => $source,
            ]);
    }

    /**
     * @param Application $application
     * @return ApplicationWinner
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function generateApplicationWinner(Application $application)
    {
        $winner = new ApplicationWinner();
        $winner->setApplication($application);
        $winner->setName($application->getName());
        $winner->setEmail($application->getEmail());
        $winner->setPhone($application->getPhone());
        $winner->setState($application->getState());

        $winner->setPhoto(UploadedFile::fake()->image('winner-photo.png'));
        $winner->setTestimonial('Testing testimonial');

        $this->em()->persist($winner);
        $this->em()->flush($winner);
        return $winner;
    }

    /**
     * @param Organisation|null $organisation
     * @param bool $generateWebsite
     * @return ScholarshipTemplate
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function generateScholarshipTemplate(Organisation $organisation = null, $generateWebsite = true)
    {
        $template = new ScholarshipTemplate();
        $template->setTitle('Testing scholarship');
        $template->setAmount(100);
        $template->setRecurrenceConfig([
            WeeklyConfig::KEY_TYPE => WeeklyConfig::TYPE,
            WeeklyConfig::KEY_START_DAY => 1,
            WeeklyConfig::KEY_DEADLINE_DAY => 3,
        ]);

        if (is_null($organisation)) {
            $organisation = $this->generateOrganisation();
        }

        $organisation->addScholarships($template);

        if ($generateWebsite) {
            $this->generateScholarshipWebsite($template);
        }

        $this->em()->persist($template);
        $this->em()->flush();

        return $template;
    }

    /**
     * @param ScholarshipTemplate $template
     * @param string|null $domain
     * @return ScholarshipWebsite
     */
    protected function generateScholarshipWebsite(ScholarshipTemplate $template, $domain = null)
    {
        $website = new ScholarshipWebsite();
        $website->setCompanyName($template->getOrganisation()->getName());
        $website->setDomain($domain ?: 'test' . str_random(8) . '.com');
        $website->setTitle('Test title');
        $website->setIntro('Test intro');
        $website->setLayout('default');
        $website->setVariant('default');
        $website->setGtm('TT-GTMIDTEST');
        $template->setWebsite($website);
        return $website;
    }

    /**
     * @param ApplicationWinner $applicationWinner
     * @return ScholarshipWinner
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function generateScholarshipWinner(ApplicationWinner $applicationWinner)
    {
        $scholarship = $applicationWinner->getApplication()->getScholarship();
        $scholarship->addWinners($winner = new ScholarshipWinner());
        $winner->setApplicationWinner($applicationWinner);
        $winner->setName('Test T.');
        $winner->setTestimonial('<b>Test</b>');
        $winner->setImage(UploadedFile::fake()->image('test.png'));
        $this->em()->flush($scholarship);
        return $winner;
    }

    /**
     * @param ScholarshipTemplate $template
     * @param Requirement $requirement
     * @return ScholarshipTemplateRequirement
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function generateTemplateRequirement(ScholarshipTemplate $template, Requirement $requirement)
    {
        $title = 'Test ' . $requirement->getName();
        $description = 'Description ' . $requirement->getName();
        $templateRequirement = new ScholarshipTemplateRequirement();
        $templateRequirement->setTitle($title);
        $templateRequirement->setDescription($description);
        $templateRequirement->setRequirement($requirement);

        $template->addRequirements($templateRequirement);

        $this->em()->flush($template);

        return $templateRequirement;
    }

    /**
     * @param TestResponse $response
     * @return array|\stdClass|\stdClass[]
     */
    protected function getJsonApiDocument(TestResponse $response)
    {
        return (new ClassHydrator())
            ->hydrate(
                (
                    new JsonApiResponse(
                        new Psr7Response($response->getStatusCode(), [], $response->getContent()),
                        new JsonDeserializer()
                    )
                )->document()
            );
    }
}
