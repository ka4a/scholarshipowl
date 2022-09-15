<?php namespace Tests\Controllers\Rest;

use App\Entities\Settings;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
{

    public function test_settings_log()
    {
        $this->actingAs($user = $this->generateUser());

        $this->json('patch', route('settings.show', Settings::CONFIG_LEGAL_AFFIDAVIT), [
            'data' => [
                'attributes' => [
                    'config' => 'test_affidavit',
                ]
            ]
        ])
            ->assertJson([
                'data' => [
                    'id' => Settings::CONFIG_LEGAL_AFFIDAVIT,
                    'type' => Settings::getResourceKey(),
                    'attributes' => [
                        'config' => 'test_affidavit',
                    ]
                ]
            ])
            ->assertOk();

        $url = route('settings.show', ['id' => Settings::CONFIG_LEGAL_AFFIDAVIT, 'include' => 'log']);
        $response = $this->json('get', $url)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => [
                        'config',
                        'createdAt',
                        'updatedAt',
                    ],
                    'relationships' => [
                        'log' => [
                            'data' => [
                                ['id', 'type'],
                                ['id', 'type'],
                            ]
                        ]
                    ]
                ]
            ])
            ->assertOk();

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('included', $data);
        $settingsLogs = array_filter($data['included'], function($item) { return $item['type'] === 'settings_log'; });
        $this->assertEquals('test_affidavit', $settingsLogs[0]['attributes']['config']);
    }

    public function test_settings_crud_actions()
    {
        $this->actingAs($user = $this->generateUser());

        $this->json('get', route('settings.index'))
            ->assertJson([
                'data' => [
                    [
                        'id' => Settings::CONFIG_LEGAL_AFFIDAVIT,
                        'type' => Settings::getResourceKey()
                    ],
                    [
                        'id' => Settings::CONFIG_LEGAL_PRIVACY_POLICY,
                        'type' => Settings::getResourceKey()
                    ],
                    [
                        'id' => Settings::CONFIG_LEGAL_TERMS_OF_USE,
                        'type' => Settings::getResourceKey()
                    ],
                ]
            ])
            ->assertOk();

        $this->json('get', route('settings.show', Settings::CONFIG_LEGAL_TERMS_OF_USE))
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => [
                        'config',
                        'createdAt',
                        'updatedAt',
                    ]
                ]
            ])
            ->assertOk();

        $this->json('patch', route('settings.show', Settings::CONFIG_LEGAL_TERMS_OF_USE), [
            'data' => [
                'attributes' => [
                    'config' => 'test_affidavit',
                ]
            ]
        ])
            ->assertJson([
                'data' => [
                    'id' => Settings::CONFIG_LEGAL_TERMS_OF_USE,
                    'type' => Settings::getResourceKey(),
                    'attributes' => [
                        'config' => 'test_affidavit',
                    ]
                ]
            ])
            ->assertOk();
    }
}
