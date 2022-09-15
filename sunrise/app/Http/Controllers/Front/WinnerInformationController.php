<?php namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Entities\Application;
use App\Services\ScholarshipManager\ContentManager;
use Doctrine\ORM\EntityManager;
use Illuminate\View\View;
use Pz\Doctrine\Rest\RestRepository;

class WinnerInformationController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContentManager
     */
    protected $contentManager;

    /**
     * IndexController constructor.
     * @param EntityManager $em
     * @param ContentManager $contentManager
     */
    public function __construct(EntityManager $em, ContentManager $contentManager)
    {
        $this->em = $em;
        $this->contentManager = $contentManager;
    }

    /**
     * @param int $id
     * @return View
     */
    public function winner($id)
    {
        if (null === $this->em->getRepository(Application::class)->find($id)) {
            abort(404);
        }

        return view('layout.admin');
    }

    /**
     * Generate and download affidavit file for specific application.
     *
     * @param string $id Application id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function affidavit($id)
    {
        /** @var RestRepository $repository */
        $repository = $this->em->getRepository(Application::class);

        /** @var Application $application */
        if (null === ($application = $repository->find($id))) {
            abort(404);
        }

        return $this->contentManager->downloadAffidavit($application->getScholarship());
    }
}
