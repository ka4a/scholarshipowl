<?php

namespace Test\Http\Controller\Index;

use App\Entity\Application;
use App\Entity\FeatureSet;
use App\Entity\PaymentMethod;
use App\Entity\Scholarship;
use App\Entity\ScholarshipStatus;
use App\Entity\Winner;
use App\Http\Controllers\Index\StripeController;
use App\Services\PaymentManager;
use App\Services\StripeService;
use App\Testing\TestCase;
use App\Testing\Traits\EntityGenerator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PHPUnit\Util\Filesystem;
use Stripe\Stripe;
use Stripe\Token;


class PubSubSunriseApplicationEndpointControllerTest extends TestCase
{
    use WithoutMiddleware;
    use EntityGenerator;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'application';
        static::$truncate[] = 'scholarship';
        static::$truncate[] = 'eligibility';
        static::$truncate[] = 'eligibility_cache';
    }


    public function testActualizeApplication_Failure()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $body = $message->getMessage();

        $application = $this->generateApplication();
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);

        //test with invalid secret
        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', 'xxx'), $body);
        $this->assertTrue($resp->status() === 403);

        //test without message
        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), []);
        $this->assertTrue($resp->status() === 400);

        // ok, but with none SOWL application
        $message->setAttribute('id', 'not-existing-id');
        $message->setAttribute('timestamp', $message->getAttribute('timestamp') + 1);
        $body = $message->getMessage();
        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
    }

    public function testActualizeApplication_applied()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $body = $message->getMessage();

        $application = $this->generateApplication();
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);


        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('application', [
            'external_application_id' => $message->getAttribute('id'),
            'external_status' => Application::EXTERNAL_STATUS_ACCEPTED,
        ]);
    }

    public function testActualizeApplication_declined()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage(null, null, 'application.status_changed');
        $message->setData('status', 'rejected');
        $body = $message->getMessage();

        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, '299a5ecf-b577-11e8-ac1a-0a580a080113', 111
        );
        $application = $this->generateApplication($scholarship);
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);


        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('application', [
            'external_application_id' => $message->getAttribute('id'),
            'external_status' => Application::EXTERNAL_STATUS_DECLINED,
        ]);
    }

    public function testActualizeApplication_winner()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $message->setAttribute('event', 'application.winner');
        $body = $message->getMessage();

        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, '299a5ecf-b577-11e8-ac1a-0a580a080113', 111
        );

        $application = $this->generateApplication($scholarship);
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);


        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('application', [
            'external_application_id' => $message->getAttribute('id'),
            'external_status' => Application::EXTERNAL_STATUS_POTENTIAL_WINNER,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getAttribute('scholarship_id'),
            'transitional_status' => Scholarship::TRANSITIONAL_STATUS_POTENTIAL_WINNER,
        ]);
    }

    public function testActualizeApplication_winnerDisqualified()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $message->setAttribute('event', 'application.winner_disqualified');
        $body = $message->getMessage();

        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, '299a5ecf-b577-11e8-ac1a-0a580a080113', 111
        );

        $application = $this->generateApplication($scholarship);
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);


        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('application', [
            'external_application_id' => $message->getAttribute('id'),
            'external_status' => Application::EXTERNAL_STATUS_DISQUALIFIED_WINNER,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getAttribute('scholarship_id'),
            'transitional_status' => Scholarship::TRANSITIONAL_STATUS_CHOOSING_WINNER,
        ]);
    }

    public function testActualizeApplication_winnerFilled()
    {
        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage();
        $message->setAttribute('event', 'application.winner_filled');
        $body = $message->getMessage();

        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, $message->getAttribute('scholarship_id'), 111
        );

        $application = $this->generateApplication($scholarship);
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);


        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('application', [
            'external_application_id' => $message->getAttribute('id'),
            'external_status' => Application::EXTERNAL_STATUS_PROVED_WINNER,
        ]);
        $this->assertDatabaseHas('scholarship', [
            'external_scholarship_id' => $message->getAttribute('scholarship_id'),
            'transitional_status' => Scholarship::TRANSITIONAL_STATUS_FINAL_WINNER,
        ]);
    }

    public function testActualizeApplication_winnerPublished()
    {
        static::$truncate[] = 'winner';

        $secretKey = config('pubsub.sunrise.push_endpoint_secret');

        $message = $this->generateMessage(null, null, 'application.winner_published');
        $message->setAttribute('event', 'application.winner_published');
        $body = $message->getMessage();
        $scholarship = $this->generateScholarship(
            ScholarshipStatus::PUBLISHED, $message->getAttribute('scholarship_id'), 111
        );
        $application = $this->generateApplication($scholarship);
        $application->setExternalApplicationId($message->getAttribute('id'));
        \EntityManager::getRepository(Application::class);
        \EntityManager::flush($application);

        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('winner', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'account_id' => $application->getAccount()->getAccountId(),
            'published' => 1,
            'won_at' => (new \DateTime($message->getData('winner')['wonDate']))->format('Y-m-d')
        ]);

        //update winner
        $message->setData('winner.name', 'Bruce Lee');
        $message->setAttribute('timestamp', $message->getAttribute('timestamp') + 1);
        $body = $message->getMessage();
        $resp = $this->postJson(route('pubsub.sunrise.actualizeApplication', $secretKey), $body);
        $this->assertTrue($resp->status() === 204);
        $this->assertDatabaseHas('winner', [
            'scholarship_id' => $scholarship->getScholarshipId(),
            'account_id' => $application->getAccount()->getAccountId(),
            'published' => 1,
            'winner_name' => 'Bruce Lee'
        ]);
    }

    private function generateMessage(
        $externalApplicationId = null,
        $externalScholarshipId = null,
        $event = null
    )
    {
        $externalApplicationId = $externalApplicationId ?? '99stfhh-6776-ee74-1242-01qwt7460975';
        $externalScholarshipId = $externalScholarshipId ?? '299a5ecf-b577-11e8-ac1a-0a580a080113';
        $event = $event ?? 'application.applied';

        $class = new class($externalApplicationId, $externalScholarshipId, $event)
        {
            protected $message;

            public function __construct($externalApplicationId, $externalScholarshipId, $event)
            {
                $this->message = [
                    'message' => [
                        'attributes' => [
                            'id' => $externalApplicationId,
                            'scholarship_id' => $externalScholarshipId,
                            'event' => $event,
                            'timestamp' => time()
                        ],
                        'data' => [
                            'name' => 'John Doe',
                            'email' => 'test@test.com',
                            'phone' => '1111111111'
                        ],
                    ],
                ];

                if ($event == 'application.winner_published') {
                    $this->message['message']['data'] = [
                        'winner' => [
                            'name' => 'John Doe',
                            'testimonial' => 'Some text of a winner testimonial',
                            'imageUrl' =>  __FILE__, //local path or a url
                            'createdAt' => (new \DateTime())->format('c'),
                            'wonDate' => Carbon::instance(new \DateTime())->addDays(5)->format('c'),
                        ],
                        'url_winner_information' => 'https://url_winner_information'
                    ];
                }
            }

            public function setAttribute($key, $value)
            {
                $this->message['message']['attributes'][$key] = $value;
                return $this;
            }

            public function getAttribute($key)
            {
                return $this->message['message']['attributes'][$key];
            }

            public function setData($key, $value)
            {
                $keys = explode('.', $key);
                $target = &$this->message['message']['data'];
                foreach ($keys as $k) {
                    $target = &$target[$k];
                }
                $target = $value;

                return $this;
            }

            public function getData($key)
            {
                return $this->message['message']['data'][$key];
            }

            public function getMessage()
            {
                $message = $this->message;
                $message['message']['data'] = base64_encode(json_encode($message['message']['data']));

                return $message;
            }
        };

        return $class;
    }
}
