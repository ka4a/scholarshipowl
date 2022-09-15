<?php namespace Tests\Feature;

use App\Console\Commands\SowlClient;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ScholarshipOwlIntegrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        Artisan::call('sowl:client');
    }

    public function test_sowl_active_scholarships()
    {
        $client = $this->getOAuthClient(SowlClient::CLIENT_NAME);

        $this->json('GET', route('scholarship.index'), [], $this->getOAuthClientHeaders('scholarships', $client))
            ->assertStatus(200);
    }

    public function test_scholarshipowl_expired_or_wrong_token()
    {
        $this->get(route('scholarship.index'))
            ->assertStatus(401);
    }

    public function test_scholarshipowl_client_check()
    {
        $client = $this->getOAuthClient(SowlClient::CLIENT_NAME);
        $this->assertEquals(SowlClient::CLIENT_NAME, $client->getName());
    }
}
