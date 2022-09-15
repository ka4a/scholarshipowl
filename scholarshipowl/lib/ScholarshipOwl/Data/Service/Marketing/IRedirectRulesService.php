<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 1/12/2015
 */

namespace ScholarshipOwl\Data\Service\Marketing;


use ScholarshipOwl\Data\Entity\Marketing\RedirectRule;
use ScholarshipOwl\Data\Entity\Marketing\RedirectRulesSet;

interface IRedirectRulesService {
	public function getRedirectRulesSet($redirectRulesSetId, $rules = true, $onlyActive = false);
	public function getRedirectRulesSets();

	public function addRedirectRulesSet(RedirectRulesSet $redirectRulesSet);
	public function updateRedirectRulesSet(RedirectRulesSet $redirectRulesSet);
	public function saveRedirectRulesSet(RedirectRulesSet $redirectRulesSet, $insert = true);
	public function deleteRedirectRulesSet($redirectRulesSetId);

	public function checkUserAgainstRules($redirectRulesSetId, $accountId);

	public function getRedirectRule($redirectRuleId);
	public function getRedirectRulesInSet($redirectRulesSetId);
	public function getRedirectRules();

	public function addRedirectRule(RedirectRule $redirectRule);
	public function updateRedirectRule(RedirectRule $redirectRule);
	public function saveRedirectRule(RedirectRule $redirectRule, $insert = true);
	public function deleteRedirectRule($redirectRuleId);
}