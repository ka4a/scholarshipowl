<?php


namespace Test\Services\Marketing;

use App\Entity\DegreeType;
use App\Entity\Marketing\RedirectRule;
use App\Services\Marketing\RedirectRulesService;
use App\Testing\TestCase;


class RedirectRulesServiceTest extends TestCase
{
    /**
     * @var RedirectRulesService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        static::$truncate[] = "redirect_rules_set";
        static::$truncate[] = "redirect_rule";

        $this->service = $this->app->make(RedirectRulesService::class);
    }

    public function testRedirectRulesAge()
    {
        $redirectRulesSet = $this->generateRedirectRulesSet();
        $redirectRule = $this->generateRedirectRule($redirectRulesSet, 0, "Age", RedirectRule::OPERATOR_GREATER, 18);
        $account = $this->generateAccount();
        $profile = $account->getProfile();

        $this->em->flush($profile->setDateOfBirth(new \DateTime('2010-01-01')));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setActive(1));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($profile->setDateOfBirth(new \DateTime('1991-01-01')));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));
    }

    public function testMultipleOperators()
    {
        $account = $this->generateAccount();
        $profile = $account->getProfile();

        $this->em->flush(
            $profile->setSchoolLevel(5)
                ->setStateName("test")
        );

        $redirectRulesSet = $this->generateRedirectRulesSet();

        $redirectRule = $this->generateRedirectRule($redirectRulesSet, 1, "school_level_id", RedirectRule::OPERATOR_GREATER, 5);
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_LESS));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setValue(6));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_NOT_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_IN));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setValue("5,6,7"));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_GREATER_EQUAL));
        $this->em->flush($redirectRule->setValue(7));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_LESS_EQUAL));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setOperator(RedirectRule::OPERATOR_LIKE));
        $this->em->flush($redirectRule->setField("state_name"));
        $this->em->flush($redirectRule->setValue("kbd"));
        $this->assertFalse($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));

        $this->em->flush($redirectRule->setValue("%tes%"));
        $this->assertTrue($this->service->checkUserAgainstRules($redirectRulesSet->getId(), $account->getAccountId()));
    }


}