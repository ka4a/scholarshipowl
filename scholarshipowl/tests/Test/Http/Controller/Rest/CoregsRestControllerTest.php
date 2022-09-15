<?php namespace Test\Http\Controller\Rest;

use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Entity\Marketing\CoregPlugin;

use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Resource\CoregsResource;
use App\Services\Marketing\CoregService;
use App\Testing\TestCase;

class CoregsRestControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = 'coreg_requirements_rule';
    }

    public function testCoregsListActions()
    {
        $firstPosition = 'register';
        $resp = $this->call('GET', route('rest::v1.coregs', $firstPosition));
        $this->assertTrue($resp->status() === 200);
        $coregPlugin = $this->generateCoregPlugin(CoregPlugin::NAME_COREG_1);

        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $resp = $this->call('GET', route('rest::v1.coregs', 'register'));
        $this->seeJsonSuccess($resp, [
            [
                'id' => $coregPlugin->getId(),
                'name' => 'Test',
                'position' => 'coreg1',
                'isVisible' => true,
                'text'  => 'Test',
                'monthlyCap' => 0,
                'html' => '',
                'js' => '',
                'extra' => []
            ]
        ]);

        $coregRequirementsRuleSet = $this->generateCoregRequirementsRuleSet();
        $coregRequirementsRule = $this->generateCoregRequirementsRule($coregRequirementsRuleSet, 1, "Age", CoregRequirementsRule::OPERATOR_GREATER, 16);
        $coregPlugin->setCoregRequirementsRuleSet($coregRequirementsRuleSet);

        $extra = [['name' => 'offer_id', 'value' => '1235061']];
        $coregPlugin->setExtra(json_encode($extra));
        $account = $this->generateAccount();
        $account->getProfile()->setDateOfBirth(new \DateTime('01-01-2000'));
        $this->em->flush();

        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $resp = $this->call('GET', route('rest::v1.coregs', [$firstPosition, $account->getAccountId()]));
        $this->seeJsonSuccess($resp, [
            [
                'id' => $coregPlugin->getId(),
                'name' => 'Test',
                'position' => 'coreg1',
                'isVisible' => true,
                'text'  => 'Test',
                'monthlyCap' => 0,
                'html' => '',
                'js' => '',
                'extra' => $extra
            ]
        ]);
    }
}
