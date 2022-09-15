<?php namespace App\Http\Controllers\Index;

use App\Entity\UnsubscribedEmail;
use App\Services\UnsubscribeEmailService;
use Illuminate\Http\Request;
use League\Flysystem\Filesystem;

class UnsubscribeController extends BaseController
{

    /**
     * @var UnsubscribeEmailService
     */
    protected $unsubscribeService;

    /**
     * UnsubscribeController constructor.
     *
     * @param UnsubscribeEmailService $service
     */
    public function __construct(UnsubscribeEmailService $service)
    {
        parent::__construct();
        $this->unsubscribeService = $service;
    }

    public function unsubscribeAction()
    {
        $unsubscribed = false;
        $email        = \Request::get('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $unsubscribedRepository = \EntityManager::getRepository(UnsubscribedEmail::class);

            if (null === ($unsubscribed = $unsubscribedRepository->findOneBy(['email' => $email]))) {
                $unsubscribed = new UnsubscribedEmail(\Request::get('email'));
                \EntityManager::persist($unsubscribed);
                \EntityManager::flush($unsubscribed);
            }

        }

        return $this->getCommonUserViewModel("pages/unsubscribe", [
            'unsubscribed' => (bool) $unsubscribed,
            'email' => $email,
        ])->send();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getUnsubscribedEmailsAction(Request $request)
    {
        if ($request->has('key') && in_array($request->get('key'), config('scholarshipowl.partner-access-keys'))) {
            return redirect($this->unsubscribeService->cloudCsv());
        }

        return $request->isMethod(Request::METHOD_POST) ?
            \Redirect::back()->with('error', 'Valid key is required') :
            $this->getCommonUserViewModel('pages/unsubscribe-list')->send();
    }
}
