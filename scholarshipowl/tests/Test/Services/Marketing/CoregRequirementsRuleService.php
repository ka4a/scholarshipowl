<?php


namespace Test\Services\Marketing;

use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Services\Marketing\RedirectRulesService;
use App\Testing\TestCase;


class CoregRequirementsRuleService extends TestCase
{
    /**
     * @var RedirectRulesService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = "coreg_requirements_rule_set";
        static::$truncate[] = "coreg_requirements_rule";

        $this->service = $this->app->make(\App\Services\Marketing\CoregRequirementsRuleService::class);
    }

    public function testRedirectRulesAge()
    {
        $coregRequirementsRuleSet = $this->generateCoregRequirementsRuleSet();
        $coregRequirementsRule = $this->generateCoregRequirementsRule($coregRequirementsRuleSet, 0, "Age", CoregRequirementsRule::OPERATOR_GREATER, 18);
        $account = $this->generateAccount();
        $profile = $account->getProfile();

        $this->em->flush($profile->setDateOfBirth(new \DateTime('2010-01-01')));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setIsShowRule(1));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($profile->setDateOfBirth(new \DateTime('1991-01-01')));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));
    }

    public function testMultipleOperators()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();

        $this->em->flush(
            $profile->setSchoolLevel(5)
                ->setStateName("test")
        );

        $coregRequirementsRuleSet = $this->generateCoregRequirementsRuleSet();

        $coregRequirementsRule = $this->generateCoregRequirementsRule($coregRequirementsRuleSet, 1, "school_level_id", CoregRequirementsRule::OPERATOR_GREATER, 5);
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_LESS));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setValue(6));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_NOT_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_IN));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setValue("5,6,7"));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_GREATER_EQUAL));
        $this->em->flush($coregRequirementsRule->setValue(7));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_LESS_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setOperator(CoregRequirementsRule::OPERATOR_LIKE));
        $this->em->flush($coregRequirementsRule->setField("state_name"));
        $this->em->flush($coregRequirementsRule->setValue("kbd"));
        $this->assertFalse($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));

        $this->em->flush($coregRequirementsRule->setValue("%tes%"));
        $this->assertTrue($this->service->checkUserAgainstRules($coregRequirementsRuleSet->getId(), $account->getAccountId()));
    }


}