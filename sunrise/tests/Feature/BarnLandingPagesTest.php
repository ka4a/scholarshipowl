<?php namespace Tests\Feature;

use App\Console\Commands\BarnClient;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplateSubscription;
use App\Repositories\ScholarshipRepository;
use App\Repositories\ScholarshipTemplateSubscriptionRepository;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use WoohooLabs\Yang\JsonApi\Hydrator\ClassHydrator;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;
use WoohooLabs\Yang\JsonApi\Serializer\JsonDeserializer;

class BarnLandingPagesTest extends TestCase
{
    protected $barnHeaders;

    public function setUp()
    {
        parent::setUp();
        $this->barnHeaders = $this->getOAuthClientHeaders('*', $this->getOAuthClient(BarnClient::CLIENT_NAME));
    }

    public function test_subscribe_to_paused_template()
    {
        $template = $this->generateScholarshipTemplate();

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        /** @var ScholarshipTemplateSubscriptionRepository $templateSubscriptionRepository */
        $templateSubscriptionRepository = $this->em()->getRepository(ScholarshipTemplateSubscription::class);

        $data = [
            'data' => [
                'attributes' => [
                    'email' => 'test@test.com',
                ]
            ]
        ];

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => ScholarshipTemplateSubscription::getResourceKey(),
                    'attributes' => [
                        'email' => $data['data']['attributes']['email'],
                        'status' => ScholarshipTemplateSubscription::STATUS_WAITING,
                    ]
                ]
            ]);

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => ScholarshipTemplateSubscription::getResourceKey(),
                    'attributes' => [
                        'email' => $data['data']['attributes']['email'],
                        'status' => ScholarshipTemplateSubscription::STATUS_WAITING,
                    ]
                ]
            ]);

        $this->mauticService->shouldReceive('notifyScholarshipPublished')
            ->once()
            ->withArgs(function($scholarship, $email) use ($scholarshipRepository, $template, $data) {
                return $scholarshipRepository->findSinglePublishedByTemplate($template) === $scholarship
                    && $email === $data['data']['attributes']['email'];
            });

        $scholarship = $this->sm()->publish($template);

        $this->assertEquals(
            ScholarshipTemplateSubscription::STATUS_NOTIFIED,
            $templateSubscriptionRepository->findOneByTemplateAndEmail(
                $template, $data['data']['attributes']['email']
            )->getStatus()
        );

        $data = [
            'data' => [
                'attributes' => [
                    'email' => 'test@test1.com',
                ]
            ]
        ];

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->sm()->unpublish($scholarship);

        $data = [
            'data' => [
                'attributes' => [
                    'email' => 'test@test2.com',
                ]
            ]
        ];

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => ScholarshipTemplateSubscription::getResourceKey(),
                    'attributes' => [
                        'email' => $data['data']['attributes']['email'],
                        'status' => ScholarshipTemplateSubscription::STATUS_WAITING,
                    ]
                ]
            ]);

        $this->mauticService->shouldReceive('notifyScholarshipPublished')
            ->once()
            ->withArgs(function($scholarship, $email) use ($scholarshipRepository, $template, $data) {
                return $scholarshipRepository->findSinglePublishedByTemplate($template) === $scholarship
                    && $email === $data['data']['attributes']['email'];
            });

        $scholarship2 = $this->sm()->publish($template);

        $this->assertEquals(
            ScholarshipTemplateSubscription::STATUS_NOTIFIED,
            $templateSubscriptionRepository->findOneByTemplateAndEmail(
                $template, $data['data']['attributes']['email']
            )->getStatus()
        );

        $data = [
            'data' => [
                'attributes' => [
                    'email' => 'test@test3.com',
                ]
            ]
        ];

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertStatus(\Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->em()->flush($template->setPaused(true));
        $this->sm()->expire($scholarship2);

        $data = [
            'data' => [
                'attributes' => [
                    'email' => 'test@test4.com',
                ]
            ]
        ];

        $url = route('scholarship_template.related.subscription.create', $template->getId());
        $this->json('post', $url, $data, $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'type' => ScholarshipTemplateSubscription::getResourceKey(),
                    'attributes' => [
                        'email' => $data['data']['attributes']['email'],
                        'status' => ScholarshipTemplateSubscription::STATUS_WAITING,
                    ]
                ]
            ]);
    }

    public function test_get_website_by_domain()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');
        $this->mauticService->shouldReceive('markContactAsWinner');

        $domain = 'www.test.com';
        $template = $this->generateScholarshipTemplate();
        $template->getWebsite()->setDomain($domain)->setDomainHosted(false);
        $website = $template->getWebsite();
        $this->em()->flush();

        $params = [
            'domain' => $domain,
            'include' => implode(',', ['template', 'scholarship', 'scholarship.content', 'scholarship.fields']),
        ];

        $this->json('get', route('scholarship_website.showByDomain', $params), [], $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $website->getId(),
                    'type' => $website->getResourceKey(),
                    'attributes' => [],
                    'relationships' => [
                        'scholarship' => [
                            'data' => null
                        ],
                        'template' => [
                            'data' => [
                                'id' => $template->getId(),
                                'type' => $template->getResourceKey(),
                            ]
                        ]
                    ]
                ]
            ]);

        $scholarship = $this->sm()->publish($template);

        $application = $this->generateApplication($scholarship, 'test@test.com');

        $response = $this->json('get', route('scholarship_website.showByDomain', $params), [], $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $website->getId(),
                    'type' => $website->getResourceKey(),
                    'attributes' => [],
                    'relationships' => [
                        'scholarship' => [
                            'data' => [
                                'id' => $scholarship->getId(),
                                'type' => $scholarship->getResourceKey(),
                            ]
                        ],
                        'template' => [
                            'data' => [
                                'id' => $template->getId(),
                                'type' => $template->getResourceKey(),
                            ]
                        ]
                    ]
                ]
            ]);

        $jsonApiResponse = new JsonApiResponse(
            new Response($response->getStatusCode(), [], $response->getContent()),
            new JsonDeserializer()
        );
        $responseWebsite = (new ClassHydrator())->hydrate($jsonApiResponse->document());

        $this->assertEquals($responseWebsite->id, $website->getId());
        $this->assertEquals($responseWebsite->domain, $website->getDomain());
        $this->assertEquals($responseWebsite->layout, $website->getLayout());
        $this->assertEquals($responseWebsite->variant, $website->getVariant());

        $this->assertEquals($responseWebsite->scholarship->id, $scholarship->getId());

        $this->assertEquals($responseWebsite->scholarship->content->id, $scholarship->getContent()->getId());
        $this->assertEquals($responseWebsite->scholarship->content->privacyPolicy, $scholarship->getContent()->getPrivacyPolicy());
        $this->assertEquals($responseWebsite->scholarship->content->termsOfUse, $scholarship->getContent()->getTermsOfUse());

        $this->assertCount(4, $responseWebsite->scholarship->fields);

        $this->sm()->expire($scholarship);

        $scholarshipWinner = $this->generateScholarshipWinner($application->getWinner());

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);
        $scholarship2 = $scholarshipRepository->findSinglePublishedByTemplate($template);

        $this->json('get', route('scholarship_website.showByDomain', $params), [], $this->barnHeaders)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $website->getId(),
                    'type' => $website->getResourceKey(),
                    'attributes' => [],
                    'relationships' => [
                        'scholarship' => [
                            'data' => [
                                'id' => $scholarship2->getId(),
                                'type' => $scholarship2->getResourceKey(),
                            ]
                        ],
                        'template' => [
                            'data' => [
                                'id' => $template->getId(),
                                'type' => $template->getResourceKey(),
                            ]
                        ]
                    ]
                ]
            ]);
    }
}
