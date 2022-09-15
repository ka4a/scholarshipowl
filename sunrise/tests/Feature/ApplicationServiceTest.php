<?php namespace Tests\Feature;

use App\Entities\Application;
use App\Entities\Field;
use App\Entities\Requirement;
use App\Entities\ScholarshipRequirement;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipTemplateRequirement;
use App\Entities\State;
use Illuminate\Http\Response;
use Illuminate\Http\Testing\File;
use Tests\TestCase;

class ApplicationServiceTest extends TestCase
{

    public function test_field_optional()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();

        /** @var ScholarshipTemplateField $stateField */
        $stateField = $template->getFields()
            ->filter(
                function(ScholarshipTemplateField $field) {
                    return $field->getField()->is(Field::STATE);
                }
            )
            ->first();

        $stateField->setOptional(true);

        $this->em()->flush($stateField);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Maho Machi',
                    'phone' => '+1 (234) 929-3333',
                    'email' => 'maho@machi.com',
                ]
            ]
        ];

        $scholarship = $this->sm()->publish($template);

        $this->json('post', route('scholarship.apply', $scholarship->getId()), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'type' => Application::getResourceKey(),
                    'attributes' => [
                        'data' => $data['data']['attributes'],
                    ]
                ]
            ]);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function test_no_state_field_scholarship()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();

        $stateField = $template->getFields()
            ->filter(
                function(ScholarshipTemplateField $field) {
                    return $field->getField()->is(Field::STATE);
                }
            )
            ->first();

        $this->em()->flush($template->removeFields($stateField));

        $scholarship = $this->sm()->publish($template);

        $data = [
            'data' => [
                'attributes' => [
                    'name' => 'Maho Machi',
                    'phone' => '+1 (234) 929-3333',
                    'email' => 'maho@machi.com',
                ]
            ]
        ];

        $this->json('post', route('scholarship.apply', $scholarship->getId()), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'type' => Application::getResourceKey(),
                    'attributes' => [
                        'data' => $data['data']['attributes'],
                    ]
                ]
            ]);
    }

    public function test_requirements_application()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $user = $this->registerUser('test@test.com222');
        $template = $this->generateScholarshipTemplate($user->getOrganisationRoles()[0]->getOrganisation());

        $this->actingAs($user);
        $this->json('put', route('scholarship_template.related.requirements.update', $template->getId()), [
            'data' => [
                [
                    'attributes' => [
                        'title' => 'TestTitle',
                        'description' => 'test description',
                        'config' => [
                            'minWords' => 5,
                            'maxWords' => 10,
                            'minChars' => 5,
                            'maxChars' => 100,
                        ]
                    ],
                    'relationships' => [
                        'requirement' => [
                            'data' => [
                                'id' => Requirement::ESSAY,
                                'type' => Requirement::getResourceKey()
                            ]
                        ]
                    ]
                ],
                [
                    'attributes' => [
                        'title' => 'TestTitle',
                        'description' => 'test description',
                    ],
                    'relationships' => [
                        'requirement' => [
                            'data' => [
                                'id' => Requirement::ESSAY,
                                'type' => Requirement::getResourceKey()
                            ]
                        ]
                    ]
                ],
                [
                    'attributes' => [
                        'title' => 'Application needed image',
                        'description' => 'Application needed image',
                        'config' => [
                            Requirement::TYPE_IMAGE_KEY_FILE_EXTENSIONS => 'jpg',
                        ]
                    ],
                    'relationships' => [
                        'requirement' => [
                            'data' => [
                                'id' => Requirement::GENERIC_PICTURE,
                                'type' => Requirement::getResourceKey(),
                            ]
                        ]
                    ]
                ]
            ]
        ])
            ->assertOk();

        $this->em()->flush($template);

        $scholarship = $this->sm()->publish($template);

        /** @var ScholarshipRequirement $essayRequirement */
        /** @var ScholarshipRequirement $essayRequirement2 */
        list($essayRequirement, $essayRequirement2) = $scholarship->getRequirements()
            ->filter(function(ScholarshipRequirement $requirement) {
                return $requirement->getRequirement()->getId() === Requirement::ESSAY;
            });

        /** @var ScholarshipRequirement $imageRequirement */
        $imageRequirement = $scholarship->getRequirements()
            ->filter(function(ScholarshipRequirement $requirement) {
                return $requirement->getRequirement()->getId() === Requirement::GENERIC_PICTURE;
            })
            ->first();

        $this->json('post', route('scholarship.apply', $scholarship->getId()), [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'requirements',
                        ],
                        'detail' => [
                            'Please enter requirements.'
                        ]
                    ],
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'requirements.'.$essayRequirement->getId()
                        ],
                        'detail' => [
                            'Please enter essay.'
                        ]
                    ]
                ]
            ]);

        $url = route('scholarship.apply', $scholarship->getId());
        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'requirements' => [
                        $essayRequirement->getId() => 'essay requirements aa bb cc!'
                    ]
                ]
            ]
        ])
            ->assertJson(['errors' => [
                [
                    'code' => 'validation',
                    'source' => [
                        'pointer' => 'requirements.'.$essayRequirement2->getId()
                    ],
                    'detail' => [
                        'Please enter essay.'
                    ]
                ]
            ]])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        /**
         * Success application
         */
        $image = File::image('test.jpg');
        $this->call('POST', route('scholarship.apply', $scholarship->getId()), [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'requirements' => [
                        $essayRequirement->getId() => 'essay requirements! ss dad dd',
                        $essayRequirement2->getId() => 'essay!',
                        $imageRequirement->getId() => $image,
                    ]
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function test_many_eligibility_rules()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_LT)
                ->setEligibilityValue(21)
        );
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_GTE)
                ->setEligibilityValue(16)
        );
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::SCHOOL_LEVEL))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_IN)
                ->setEligibilityValue('1,2,3')
        );

        $this->em()->flush($template);
        $scholarship = $this->sm()->publish($template);
        $url = route('scholarship.apply', $scholarship->getId());

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'dateOfBirth' => (new \DateTime('-23 year'))->format('Y-m-d'),
                    'schoolLevel' => 4
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'dateOfBirth',
                        ],
                        'detail' => [
                            'The age must be less than 21.'
                        ]
                    ],
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'schoolLevel',
                        ],
                        'detail' => [
                            'The school level must be one of "High school freshman", "High school sophomore", "High school junior".'
                        ]
                    ],
                ]
            ]);
    }

    public function test_date_of_birth_field()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $template->addFields(
            (new ScholarshipTemplateField())
                ->setField(Field::find(Field::DATE_OF_BIRTH))
                ->setEligibilityType(ScholarshipTemplateField::ELIGIBILITY_TYPE_GTE)
                ->setEligibilityValue(16)
        );

        $this->em()->flush($template);
        $scholarship = $this->sm()->publish($template);
        $url = route('scholarship.apply', $scholarship->getId());

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'dateOfBirth',
                        ],
                        'detail' => [
                            'Please enter date of birth.'
                        ]
                    ],
                ]
            ]);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'dateOfBirth' => '19/02/20'
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'dateOfBirth',
                        ],
                        'detail' => [
                            'The date of birth is not a valid date.'
                        ]
                    ],
                ]
            ]);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'dateOfBirth' => (new \DateTime('-15 year'))->format('Y-m-d'),
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'dateOfBirth',
                        ],
                        'detail' => [
                            'The age must be bigger or equal 16.'
                        ]
                    ],
                ]
            ]);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                    'dateOfBirth' => (new \DateTime('-16 year'))->format('Y-m-d'),
                ]
            ]
        ])
            ->assertJson([
                'data' => [
                    'type' => Application::getResourceKey(),
                    'attributes' => [
                        'data' => [
                            'dateOfBirth' => (new \DateTime('-16 year'))->format('Y-m-d'),
                        ]
                    ]
                ]
            ])
            ->assertStatus(Response::HTTP_CREATED);
    }

    public function test_basic_fields_validation_errors()
    {
        $this->mauticService->shouldReceive('syncApplication');
        $this->mauticService->shouldReceive('notifyApplied');

        $template = $this->generateScholarshipTemplate();
        $scholarship = $this->sm()->publish($template);
        $url = route('scholarship.apply', $scholarship->getId());

        $this->json('post', $url, [
            'data' => [
                'attributes' => []
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'name',
                        ],
                        'detail' => [
                            'Please enter name.'
                        ]
                    ],
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'phone',
                        ],
                        'detail' => [
                            'Please enter phone.'
                        ]
                    ],
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'email',
                        ],
                        'detail' => [
                            'Please enter email.'
                        ]
                    ],
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'state',
                        ],
                        'detail' => [
                            'Please enter state.'
                        ]
                    ],
                ]
            ]);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => '99',
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'state',
                        ],
                        'detail' => [
                            'The selected state is invalid.'
                        ]
                    ],
                ]
            ]);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->json('post', $url, [
            'data' => [
                'attributes' => [
                    'name' => 'Test LastName',
                    'email' => 'test@test.com',
                    'phone' => '+1 (484) 384 - 8488',
                    'state' => State::STATE_ALABAMA,
                ]
            ]
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'errors' => [
                    [
                        'code' => 'validation',
                        'source' => [
                            'pointer' => 'email',
                        ],
                        'detail' => [
                            'Student with such email already applied for the scholarship!'
                        ]
                    ],
                ]
            ]);
    }
}

