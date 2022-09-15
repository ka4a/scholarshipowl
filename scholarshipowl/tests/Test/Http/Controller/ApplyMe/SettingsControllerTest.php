<?php
# CrEaTeD bY FaI8T IlYa
# 2016

namespace Test\Http\Controller\ApplyMe;

use App\Entity\ApplyMe\ApplymeSettings;
use App\Entity\Domain;
use App\Entity\Resource\ApplyMe\SettingsResource;
use App\Testing\TestCase;
use ScholarshipOwl\Data\ResourceCollection;

class SettingsControllerTest extends TestCase
{
	protected $V = 'v2';

    /**
     * Register first step
     */
    public function testGet()
    {
        $this->actingAs($account = $this->generateAccount(
            $email = 'test@test.com',
            $firstName = 'testFirstName',
            $lastName = 'testLastName',
            $password = 'testPassword',
            $domain  = Domain::APPLYME
        ));

        $resp = $this->get(route('apply-me-api::' . $this->V . '.settings.index'));

        /** @var ApplymeSettings $settings */
		$settings = \EntityManager::getRepository(ApplymeSettings::class)->findAll();
		$resource = new ResourceCollection(new SettingsResource(), $settings);

        $this->assertTrue($resp->status() === 200);
        $this->seeJsonContains($resp, $resource->toArray());
    }
}
