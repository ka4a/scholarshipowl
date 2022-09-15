<?php namespace Tests\Controllers\Rest;

use App\Entities\Organisation;
use App\Entities\OrganisationRole;
use App\Entities\Scholarship;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ScholarshipTemplateControllerTest extends TestCase
{
    public function test_scholarship_template_relationships_content()
    {
        $this->actingAs($user = $this->generateUser());
        $template = $this->generateScholarshipTemplate();

        $this->json('get', route('scholarship_template.related.content.show', $template->getId()))
            ->assertOk()
            ->assertJsonMissing([
                'included'
            ])
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'links' => ['self']
                    ],
                    [
                        'id',
                        'type',
                        'links' => ['self']
                    ],
                    [
                        'id',
                        'type',
                        'links' => ['self']
                    ],
                ]
            ]);
    }

    public function test_scholarship_template_related_scholarship()
    {
        $this->actingAs($user = $this->generateUser(1, false));
        $organisation = $this->generateOrganisation('Test org', $user);
        $template = $this->generateScholarshipTemplate($organisation);
        $scholarship = $this->sm()->publish($template);

        $this->json('get', route('scholarship_template.related.scholarship.show', $template->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => Scholarship::getResourceKey(),
                    ]
                ],
            ]);

        $this->sm()->unpublish($scholarship);
        $scholarship2 = $this->sm()->publish($template);

        $this->json('get', route('scholarship_template.related.scholarship.show', $template->getId()))
            ->assertOk()
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => Scholarship::getResourceKey(),
                    ],
                    [
                        'id' => $scholarship2->getId(),
                        'type' => Scholarship::getResourceKey(),
                    ]
                ],
            ]);

        $params = ['id' => $template->getId(), 'filter' => ['expiredAt' => ['operator' => 'neq', 'value' => '']]];
        $this->json('get', route('scholarship_template.related.scholarship.show', $params))
            ->assertOk()
            ->assertJsonMissing(['id' => $scholarship2->getId()])
            ->assertJson([
                'data' => [
                    [
                        'id' => $scholarship->getId(),
                        'type' => Scholarship::getResourceKey(),
                    ],
                ],
            ]);
    }

    public function test_scholarship_template_user_manage()
    {
        $this->actingAs($user = $this->generateUser(1, false));
        $organisation = $this->generateOrganisation('Test organisation', $user);

        $scholarshipData = [
            'data' => [
                'attributes' => [
                    'title' => 'Test title',
                    'description' => 'Test title',
                    'amount' => 500,
                    'awards' => 2,
                    'timezone' => Scholarship::DEFAULT_TIMEZONE,
                ],
                'relationships' => [
                    'organisation' => [
                        'data' => [
                            'id' => ''.$organisation->getId(),
                            'type' => $organisation->getResourceKey(),
                        ]
                    ]
                ]
            ]
        ];

        $url = route('scholarship_template.create', ['include' => 'organisation']);
        $response = $this->json('post', $url, $scholarshipData)
            ->assertStatus(201)
            ->assertJson($scholarshipData);

        $scholarshipTemplate = json_decode($response->getContent());
        $scholarshipWebsite = [
            'data' => [
                'attributes' => [
                    'domain' => 'test-domain',
                    'layout' => 'test',
                    'variant' => 'test',
                    'companyName' => $organisation->getName(),
                    'title' => $scholarshipTemplate->data->attributes->title,
                    'intro' => $scholarshipTemplate->data->attributes->description,
                ]
            ]
        ];

        $url = route('scholarship_template.related.website.update', ['id' => $scholarshipTemplate->data->id]);
        $this->json('post', $url, $scholarshipWebsite)
            ->assertStatus(200)
            ->assertJson($scholarshipWebsite);

        $scholarshipWebsite = [
            'data' => [
                'attributes' => [
                    'domain' => 'test-domain',
                    'layout' => 'test',
                    'variant' => 'test',
                    'logo' => File::image('testfile.jpg'),
                    'companyName' => 'Test organisation',
                    'title' => $scholarshipTemplate->data->attributes->title,
                    'intro' => $scholarshipTemplate->data->attributes->description,
                ]
            ]
        ];

        $url = route('scholarship_template.related.website.update', ['id' => $scholarshipTemplate->data->id]);
        $this->json('patch', $url, $scholarshipWebsite)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'domain' => 'test-domain',
                        'layout' => 'test',
                        'variant' => 'test',
                        'companyName' => 'Test organisation',
                        'title' => $scholarshipTemplate->data->attributes->title,
                        'intro' => $scholarshipTemplate->data->attributes->description,
                    ],
                    'relationships' => [
                        'logo' => []
                    ]
                ]
            ]);
    }
}
