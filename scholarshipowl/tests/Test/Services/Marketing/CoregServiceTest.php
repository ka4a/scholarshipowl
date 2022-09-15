<?php


namespace Test\Services\Marketing;


use App\Entity\Country;
use App\Entity\Marketing\CoregPlugin;
use App\Services\Marketing\CoregService;
use App\Testing\TestCase;

class CoregServiceTest extends TestCase
{
    /**
     * @var CoregService
     */
    protected $coregService;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = "coreg_plugins";
        static::$truncate[] = "coreg_plugin_allocation";
        static::$truncate[] = "coreg_requirements_rule_set";
        static::$truncate[] = "coreg_requirements_rule";

        $this->coregService = $this->app->make(CoregService::class);
    }

    public function testGetCoregPlugins()
    {
        $coregPlugin = $this->generateCoregPlugin(CoregPlugin::NAME_COREG_1, false);

        $this->assertEmpty($this->coregService->getCoregPluginsByPosition());

        $this->em->flush($coregPlugin->setIsVisible(true));
        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertTrue($this->coregService->getCoregPluginsByPosition()->contains($coregPlugin));

        $this->em->flush($coregPlugin->setDisplayPosition(CoregPlugin::NAME_COREG_5));
        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertEmpty($this->coregService->getCoregPluginsByPosition());
        $this->assertTrue($this->coregService->getCoregPluginsByPosition("register3")->contains($coregPlugin));

        $this->em->flush($coregPlugin->setDisplayPosition(CoregPlugin::NAME_COREG_1));

        $coregRequirementsRuleSet = $this->generateCoregRequirementsRuleSet();
        $this->em->flush($coregPlugin->setCoregRequirementsRuleSet($coregRequirementsRuleSet));

        $this->generateCoregRequirementsRule($coregRequirementsRuleSet, 1);
        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertTrue($this->coregService->getCoregPluginsByPosition(null, null)->contains($coregPlugin));

        $account = $this->generateAccount();
        $profile = $account->getProfile();

        $this->em->flush($profile->setSchoolLevel(3));

        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertEmpty($this->coregService->getCoregPluginsByPosition(null, $account));

        $this->em->flush($profile->setSchoolLevel(5));
        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertTrue($this->coregService->getCoregPluginsByPosition(null, $account)->contains($coregPlugin));

        $this->em->flush($profile->setCountry(Country::CANADA));
        \Cache::tags(CoregService::CACHE_TAGS)->flush();
        $this->assertEmpty($this->coregService->getCoregPluginsByPosition(null, $account));
    }

    public function testCoregPluginAllocation()
    {
        $coregPlugin = $this->generateCoregPlugin(CoregPlugin::NAME_COREG_1, false);

        $this->assertEquals(false, $this->coregService->getRemainingPluginCap($coregPlugin));

        $this->em->flush($coregPlugin->setMonthlyCap(10));
        $this->assertEquals($this->coregService->getRemainingPluginCap($coregPlugin), 10);

        $this->coregService->updateCoregPluginAllocation($coregPlugin);
        $this->assertEquals($this->coregService->getRemainingPluginCap($coregPlugin), 9);

        $this->coregService->updateCoregPluginAllocation($coregPlugin);
        $this->assertEquals($this->coregService->getRemainingPluginCap($coregPlugin), 8);
    }
}
