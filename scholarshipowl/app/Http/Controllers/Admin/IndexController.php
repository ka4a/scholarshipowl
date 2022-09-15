<?php namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Entity\Admin\Admin;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\Resource;
use App\Entity\Scholarship;

/**
 * Index Controller for admin
 */
class IndexController extends BaseController
{
	/**
	 * Dashboard Action
	 *
     * @return \Illuminate\Contracts\View\View
     */
	public function indexAction()
    {
        /** @var AccountRepository $accountRepository **/
        $accountRepository = \EntityManager::getRepository(Account::class);

        /** @var ScholarshipRepository $scholarShipRepository $ */
        $scholarShipRepository = \EntityManager::getRepository(Scholarship::class);

        return $this->view('Dashboard', 'admin.dashboard.index', [
            'scholarships' => $scholarShipRepository->findLatestScholarships(),
            'accounts' => Resource::getResourceCollection($accountRepository->findLatestAccounts()),
        ]);
	}

    /**
     * Login Action
     *
     * @return \Illuminate\Contracts\View\View
     */
	public function loginAction()
    {
        if (\Auth::guard('admin')->user()) {
            return redirect()->intended(route('admin::index.index'));
        }

        return view('admin.dashboard.login');
	}

	/**
	 * Post Login Action
	 *
     * @return \Illuminate\Http\JsonResponse
     */
	public function postLoginAction()
    {
        $redirectUrl = null;
        $credentials = array(
            "email" => $this->getQueryParam("email"),
            "password" => $this->getQueryParam("password"),
            "status" => Admin::STATUS_ACTIVE,
        );

        $error = "";

        if (\Auth::guard('admin')->attempt($credentials)) {
            $redirectUrl = \URL::previous(route('admin::index.index'));
        }else{
            $error = "Password and email does not match";
        }

        return \Response::json(['status' => 'redirect', 'data' => $redirectUrl,
                                "error" => $error]);
    }

    /**
     * Logout Action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutAction()
    {
        \Auth::guard('admin')->logout();
        return \Redirect::route('admin::index.login');
    }
}
