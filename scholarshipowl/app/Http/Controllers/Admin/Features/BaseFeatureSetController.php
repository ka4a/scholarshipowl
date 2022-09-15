<?php
namespace App\Http\Controllers\Admin\Features;

use App\Entity\FeatureSet;
use App\Entity\Repository\EntityRepository;
use App\Http\Controllers\Admin\BaseController;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Validator;

class BaseFeatureSetController extends BaseController {

    /**
     * @var string
     */
    protected $entity = FeatureSet::class;

    /**
     * Contains route to index action for current controller.
     * Need for redirect in clone action
     *
     * @var string
     */
    protected $indexRoute = 'admin::features.index';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * PaymentSetController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->repository = $em->getRepository($this->entity);
    }

    /**
     * @param $id
     * @param $name
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clone($id, $name = '')
    {
        $fSet = $this->repository->findById($id);

        if ($name == '') {
            $name = "copy_".$fSet->getName();
        }

        $validator = Validator::make(['name' => $name], [
            'name' => 'required|unique:'.$this->entity,
        ]);

        if ($validator->fails()) {
            return \Redirect::route($this->indexRoute)
                ->with('error', sprintf('Name must be unique, provided [ %s ]', $name));
        }

        if ($fSet->getName() != $name) {
            $clonedFset = clone $fSet;
            $clonedFset->setName($name);
            $this->em->detach($clonedFset);
            $this->em->persist($clonedFset);
            $this->em->flush();
        }

        return \Redirect::route($this->indexRoute)
            ->with('message', sprintf('Feature set cloned with new name [ %s ]', $name));
    }
}