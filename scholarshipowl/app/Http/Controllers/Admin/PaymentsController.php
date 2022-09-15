<?php namespace App\Http\Controllers\Admin;

use App\Entity\BraintreeAccount;
use App\Entity\Setting;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Service\Website\SettingService;

class PaymentsController extends BaseController
{
    public function braintreeAccountsAction()
    {
        /** @var BraintreeAccount[] $accounts */
        $accounts = \EntityManager::getRepository(BraintreeAccount::class)->findAll();

        $accountsOptions = [];
        foreach ($accounts as $account) {
            $accountsOptions[$account->getId()] = sprintf('%s (%s)', $account->getName(), $account->getId());
        }

        return $this->view('Braintree Accounts', 'admin.payments.braintree.index', [
            'accounts' => $accounts,
            'accountsOptions' => $accountsOptions,
            'default' => \Setting::get(BraintreeAccount::SETTING_DEFAULT_ACCOUNT),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveBraintreeDefaultSetting(Request $request)
    {
        $this->validate($request, ['default' => 'numeric']);

        \Setting::set(BraintreeAccount::SETTING_DEFAULT_ACCOUNT, $request->get('default'));

        return \Redirect::back()->with('message', 'Default account saved!');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveBraintreeAccount(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|string',
            'merchantId' => 'required|string',
            'publicKey'  => 'required|string',
            'privateKey' => 'required|string',
        ]);

        $this->addBreadcrumb('Braintree Accounts', 'payments.braintree.index');

        \EntityManager::persist($braintreeAccount = new BraintreeAccount(
            $request->get('name'),
            $request->get('merchantId'),
            $request->get('publicKey'),
            $request->get('privateKey')
        ));
        \EntityManager::flush();

        return \Redirect::back()->with('message', 'Account saved!');
    }
}
