<?php namespace Tests\Controllers\Rest;

use App\Entities\Iframe;
use Tests\TestCase;

class IframeControllerTest extends TestCase
{
    public function test_base_iframe_crud_actions()
    {
        $this->actingAs($user = $this->registerUser());
        $template = $this->generateScholarshipTemplate($user->getOrganisationRoles()->first()->getOrganisation());

        $response = $this->json('post', route('iframe.create', ['include' => 'template']), [
            'data' => [
                'relationships' => [
                    'template' => [
                        'data' => [
                            'id' => $template->getId(),
                            'type' => $template->getResourceKey(),
                        ]
                    ]
                ]
            ]
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => Iframe::getResourceKey(),
                    'attributes' => [
                        'width' => '100%',
                        'height' => null,
                        'source' => 'iframe',
                    ],
                    'relationships' => [
                        'template' => [
                            'data' => [
                                'id' => ''.$template->getId(),
                                'type' => $template->getResourceKey(),
                            ]
                        ]
                    ],
                ]
            ]);

        $response = json_decode($response->getContent(), true);

        $this->assertIsString($response['data']['id']);
        $this->assertEquals(Iframe::getResourceKey(), $response['data']['type']);

        $this->json('patch', route('iframe.update', $response['data']['id']), [
            'data' => [
                'attributes' => [
                    'width' => '100%',
                    'height' => '800',
                    'source' => 'iframe',
                ]
            ]
        ])
            ->assertOk();

        $url = route('iframe.show', ['id' => $response['data']['id'], 'include' => 'template']);
        $this->json('get', $url)
            ->assertOk()
            ->assertJson([
                'data' => [
                    'attributes' => [
                        'width' => '100%',
                        'height' => '800',
                        'source' => 'iframe',
                    ],
                    'relationships' => [
                        'template' => [
                            'data' => [
                                'id' => $template->getId(),
                                'type' => $template->getResourceKey(),
                            ]
                        ]
                    ],
                ]
            ]);

        $this->json('delete', route('iframe.delete', $response['data']['id']))->assertStatus(204);
        $this->em()->clear(Iframe::class);
        $this->json('get', route('iframe.show', $response['data']['id']))->assertStatus(404);

    }
}