<?php namespace ScholarshipOwl\Events;

use App\Events\Account\Register3AccountEvent;
use Illuminate\Events\Dispatcher;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;
use ScholarshipOwl\Http\JsonModel;

class LoanEventHandler
{
	private $pluginName = "loan";

    /**
     * After saving account browser should open loan popup if customer under 18 years old.
     *
     * @param JsonModel $jsonModel
     * @param Account $account
     */
    public function onRegister3(Register3AccountEvent $event)
    {
        $jsonModel = $event->getModel();
        $account = $event->getAccount();

        if ($jsonModel && $jsonModel->getStatus() === JsonModel::STATUS_REDIRECT) {
            if ($dateOfBirth = DateHelper::fromString($account->getProfile()->getDateOfBirth())) {

				$plugin = \Session::get("plugin.".$this->pluginName);

				if($plugin->getRedirectRulesSetId()){
					$redirectRulesService = new RedirectRulesService();
					if($redirectRulesService->checkUserAgainstRules($plugin->getRedirectRulesSetId(), $account->getAccountId())){
						$jsonModel->setStatus(JsonModel::STATUS_REDIRECT_POPUP);
						$jsonModel->setData(array(
							'redirect' => $jsonModel->getData(),
							'redirect_popup' => route('loanAction'),
						));
					}
				}
            }
        }
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Register3AccountEvent::class, static::class . '@onRegister3');
    }
}
