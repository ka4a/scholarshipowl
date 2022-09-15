<?php namespace Test\Http\Controller\Rest;

use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Ethnicity;
use App\Entity\Profile;
use App\Entity\SchoolLevel;
use App\Entity\State;
use App\Services\OptionsManager;
use App\Services\PubSub\AccountService;
use App\Testing\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountFieldsControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        static::$truncate = ['account_login_token'];
    }

    public function testListAccountFields()
    {
        // 401 without api key
        $resp = $this->get(route('rest-external::v1.list-account-fields'));
        $this->assertTrue($resp->status() === 401);

        $secret = config('services.mautic.api_key');
        $resp = $this->get(route('rest-external::v1.list-account-fields'), ['X-API-KEY' => $secret]);
        $this->assertTrue($resp->status() === 200);
        $this->assertTrue(in_array(AccountService::FIELD_FIRST_NAME, $resp->getData()->data));
        $this->assertTrue($resp->getData()->data == array_values(AccountService::fields()));
    }

    public function testGetAndRefreshAccountFields()
    {
        // 401 without api key
        $resp = $this->post(route('rest-external::v1.get-account-fields'));
        $this->assertTrue($resp->status() === 401);

        $account = $this->generateAccount();

        $data = [
            'accounts' => [$account->getAccountId()],
            'fields' => [
                'login_token',
                'scholarship_eligible_count'
            ]
        ];

        $secret = config('services.mautic.api_key');

        $resp = $this->post(
            route('rest-external::v1.get-account-fields'),
            $data,
            ['X-API-KEY' => $secret]
        );
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                $account->getAccountId() => [
                   'login_token',
                   'scholarship_eligible_count'
                ]
            ]
        ]);


        // check that on login_token was regenerated
        $resp2 = $this->post(
            route('rest-external::v1.get-account-fields'),
            $data,
            ['X-API-KEY' => $secret]
        );
        $this->assertTrue($resp->status() === 200);
        $token1 = $this->decodeResponseJson($resp)['data'][$account->getAccountId()]['login_token'];
        $token2 = $this->decodeResponseJson($resp2)['data'][$account->getAccountId()]['login_token'];
        $this->assertTrue(!empty($token1) && !empty($token2) && $token1 !== $token2);


        // check all fields
        $fields = array_values(AccountService::fields());
        $data = [
            'accounts' => [$account->getAccountId()],
            'fields' => $fields
        ];
        $resp = $this->post(
            route('rest-external::v1.get-account-fields'),
            $data,
            ['X-API-KEY' => $secret]
        );
        $this->assertTrue($resp->status() === 200);
        $this->seeJsonStructure($resp, [
            'status',
            'data' => [
                $account->getAccountId() => $fields
            ]
        ]);
    }
}
