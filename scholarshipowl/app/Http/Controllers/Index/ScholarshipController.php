<?php namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Services\Marketing\CoregService;
use Doctrine\ORM\EntityManager;

class ScholarshipController extends BaseController
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * @var CoregService
     */
    protected $coregService;

    /**
     * ScholarshipController constructor.
     *
     * @param EntityManager $em
     * @param CoregService  $cs
     */
    public function __construct(EntityManager $em, CoregService $cs)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(Scholarship::class);
        $this->coregService = $cs;
    }

    public function scholarshipAction($id, $slug)
    {
        $view = "scholarship/scholarship";

        /** @var Scholarship $scholarship */
        $scholarship = \EntityManager::find(Scholarship::class, $id);

        abort_if(!$scholarship || !$scholarship->getTitle() || $scholarship->getTitleSlug() !== $slug, 404);

        if ($scholarship->isExpired() && $scholarship->isRecurrent() && $scholarship->getCurrentScholarship()) {
            return redirect($scholarship->getCurrentScholarship()->getPublicUrl(), 301);
        }

        $isPublished = $this->repository->isPublished([$id])[$id];
        
        \Session::flash("payment_return", \URL::current());
        return $this->getCommonUserViewModel($view, [
            "social" => true,
            "scholarship" => $scholarship,
            "isPublished" => $isPublished,
            "coregs" => $this->coregService->getCoregsByPath('register'),
        ])->send();
    }

    public function scholarshipExpiredAction()
    {
        $view = "scholarship/expired";

        return $this->getCommonUserViewModel($view, [
            "social" => true,
            "coregs" => $this->coregService->getCoregsByPath('register'),
        ])->send();
    }

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function scholarshipsAction() {
        if (!($account = \Auth::user()) || !$account instanceof Account || !$account->isMember()) {
            if (setting("scholarships.redirect_free") != "scholarships" && setting("scholarships.redirect_free") != "default") {
                return \Redirect::to(setting("scholarships.redirect_free"));
            }
        } else if (setting("scholarships.redirect_members") != "scholarships" && setting("scholarships.redirect_members") != "default") {
            return \Redirect::to(setting("scholarships.redirect_members"));
        }

        $this->registerHasOffers('select');

        return $this->getCommonUserViewModel('layout-vue', [
            "coregs" => $this->coregService->getCoregsByPath('register'),
        ])->send();
    }
}
