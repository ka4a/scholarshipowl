<?php

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Marketing\MarketingSystemAccount;


interface IMarketingSystemService {
	public function setMarketingSystemAccount(MarketingSystemAccount $marketingSystemAccount);
	public function getMarketingSystemAccount($accountId, $data = true);
	
	public function search($params = array(), $limit = "");
	
	public function getMarketingSystemParametersByAccountIds($accountIds);
}
