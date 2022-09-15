<?php namespace Test\Http\Controller\Rest;

use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Entity\Marketing\CoregPlugin;

use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Resource\CoregsResource;
use App\Services\Marketing\CoregService;
use App\Testing\TestCase;
use Carbon\Carbon;

class SettingsRestControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testSettingsActions()
    {
        $resp = $this->call('GET','http://scholarship.app/rest/v1/settings-public' /* route('rest::v1.settings')*/);
        $this->assertTrue($resp->status() === 200);

        $resp = $this->call('GET','http://scholarship.app/rest/v1/settings-private' /* route('rest::v1.settings')*/);
        $this->assertTrue($resp->status() === 401);

        $this->actingAs($account = $this->generateAccount('test@test2.com'));

        $subscription = $this->generateSubscription();
        $account->addSubscription($subscription);
        $resp = $this->call('GET','http://scholarship.app/rest/v1/settings-private' /* route('rest::v1.settings')*/);
        $this->assertTrue($resp->status() === 200);

        $this->seeJsonStructure($resp, [
           'status',
           'data' => [
               'memberships.active_text',
               'memberships.cancelled_text',
               'memberships.free_trial_active_text',
               'memberships.freeTrial.cancel_subscription',
               'memberships.cancel_subscription_text'
           ]
        ]);

        $data = json_decode($resp->getContent(), true)['data'];
        $today = Carbon::today()->format('F jS, Y');
        $this->assertTrue(strpos($data['memberships.cancelled_text'], $today) !== false);
        $this->assertTrue(strpos($data['memberships.cancel_subscription_text'], $today) !== false);

        $resp = $this->call('GET','http://scholarship.app/rest/v1/settings-private', ['fields' => 'memberships.active_text'] );
        $this->assertEquals(in_array('memberships.active_text', array_keys(json_decode($resp->getContent(), true)['data'])), 1);
    }
}
