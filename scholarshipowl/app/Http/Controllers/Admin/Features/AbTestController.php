<?php namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureAbTest;
use App\Entity\FeatureSet;
use App\Entity\Repository\EntityRepository;
use App\Entity\Repository\FeatureAbTestRepository;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Middleware\FeatureAbTestsMiddleware;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

class AbTestController extends BaseController
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FeatureAbTestRepository
     */
    protected $repository;

    /**
     * AbTestController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->repository = $em->getRepository(FeatureAbTest::class);
    }

    public function index()
    {
        $tests = $this->repository->findAll();

        $this->addBreadcrumb('Features', 'features.index');
        $this->addBreadcrumb('Ab Tests', 'features.ab_tests.index');

        return $this->view('Ab Tests', 'admin.features.ab_tests.index', [
            'tests' => $tests,
        ]);
	}

    public function edit(Request $request, $id = null)
    {
        /** @var FeatureAbTest $abTest */
        $abTest = $id !== null ? $this->repository->findById($id) : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'name'              => 'required|max:255',
                'feature_set'       => 'required|exists:' . FeatureSet::class . ',id',
                'config.percentage' => 'required|numeric|min:1|max:99',
                'enabled'           => 'required|boolean',
            ]);

            if ($abTest) {
                $abTest->setName($request->get('name'));
                $abTest->setFeatureSet($request->get('feature_set'));
                $abTest->setConfig($request->get('config'));
                $abTest->setEnabled($request->get('enabled') === '1');

                $this->em->flush($abTest);

                \Cache::tags(FeatureAbTestsMiddleware::FEATURE_SET_CACHE_TAG)->flush();
            } else {
                $abTest = new FeatureAbTest(
                    $request->get('name'),
                    $request->get('feature_set'),
                    $request->get('config')
                );

                $abTest->setEnabled($request->get('enabled') === '1');

                $this->em->persist($abTest);
                $this->em->flush($abTest);
            }

            return \Redirect::route('admin::features.ab_tests.edit', $abTest->getId())
                ->with('message', sprintf('Ab test \'%s\' saved!', $abTest));
        }

        return $this
            ->addBreadcrumb('Features', 'features.index')
            ->addBreadcrumb('Ab Tests', 'features.ab_tests.index')
            ->addPostBreadcrumb('features.ab_tests.edit', $id)
            ->view(($id ? 'Edit ' : 'Create ') . 'Ab Test', 'admin.features.ab_tests.edit', [
                'test' => $abTest,
            ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $this->em->remove($test = $this->repository->findById($id));
        $this->em->flush();

        return \Redirect::route('admin::features.ab_tests.index')
            ->with('message', sprintf('Ab test \'%s\' removed!', $test));
    }
}
