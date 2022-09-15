<?php namespace App\Http\Controllers\Admin\Marketing;

use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Entity\Marketing\Coreg\CoregRequirementsRuleSet;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Repository\EntityRepository;
use App\Http\Controllers\Admin\BaseController;
use App\Services\Marketing\CoregRequirementsRuleService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use ScholarshipOwl\Http\JsonModel;

class CoregsController extends BaseController
{

    const DROPDOWN_FIRST_ELEMENT = "--- Select ---";
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $coregRepository;

    /**
     * @var EntityRepository
     */
    protected $coregRequirementsSetRepository;

    /**
     * @var EntityRepository
     */
    protected $redirectRuleSetRepository;

    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->coregRepository = $em->getRepository(CoregPlugin::class);
        $this->coregRequirementsSetRepository = $em->getRepository(CoregRequirementsRuleSet::class);
        $this->redirectRuleSetRepository = $em->getRepository(RedirectRulesSet::class);
    }

    /**
     * @return mixed
     */
    public function coregPluginAction()
    {
       $this->addBreadcrumb('Dashboard', 'index.index');
       $this->addBreadcrumb('Marketing', 'marketing.index');
       $this->addBreadcrumb('Coreg Plugin', 'marketing.coregs.list');

        $data = ["coreg_plugins" => array()];

        try {
            $data["coreg_plugins"] = $this->coregRepository->findAll();
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        return $this->view('Coreg Plugin', 'admin.marketing.coreg_plugin', $data);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function saveCoregPluginAction(Request $request)
    {
        $this->addBreadcrumb('Dashboard', 'index.index');
        $this->addBreadcrumb('Marketing', 'marketing.index');
        $this->addBreadcrumb('Coreg Plugin', 'marketing.coregs.list');

        $data = [
            "coreg_plugin" => new CoregPlugin(),
            'coreg_requirements' => new CoregRequirementsRuleSet(),
            "rule_types" => array("" => self::DROPDOWN_FIRST_ELEMENT) + CoregRequirementsRuleSet::getRedirectRulesSetTypes(),
            "profile_fields" => array("" => self::DROPDOWN_FIRST_ELEMENT),
            "operators" => array("" => self::DROPDOWN_FIRST_ELEMENT) + CoregRequirementsRule::getCoregRequirementsRuleOperators(),
            "flag_selector" => array("" => self::DROPDOWN_FIRST_ELEMENT, "1" => "Yes", "0" => "No"),
            "display_positions" => CoregPlugin::getPositions(),
            "names" => CoregPlugin::getNames(),
        ];

        $title = "Add Coreg Plugin";

        try {
            $id = $request->get("id");

            if (empty($id)) {
                $this->addBreadcrumb('Add Coreg Plugin', 'marketing.coregs.save');
            } else {
                $title = "Edit Coreg Plugin";
                /**
                 * @var CoregPlugin $coregPlugin
                 */
                $coregPlugin = $this->coregRepository->findById($id);
                if(empty($coregPlugin->getCoregRequirementsRuleSet())){
                    $coregPlugin->setCoregRequirementsRuleSet([new CoregRequirementsRuleSet()]);
                }

                $data["coreg_plugin"] = $coregPlugin;
                $this->addPostBreadcrumb('marketing.coregs.post-save', $id);
            }

            $profileFields = ["" => self::DROPDOWN_FIRST_ELEMENT];

            $data["profile_fields"] = $this->getProfileFields($profileFields);
            $data["flash"] = $this->getFlashData();
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        return $this->view($title, 'admin.marketing.coregs.coreg_plugin_save', $data);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function postSaveCoregPluginAction(Request $request)
    {
        try {
            $url = "admin::marketing.coregs.save";
            $id = $request->get("coreg_plugin_id");

            if ($id) {
                /**
                 * @var CoregPlugin $coregPlugin
                 */
                $coregPlugin = $this->coregRepository->findById($id);
                $coregName = $coregPlugin->getName();
                $message = "Coreg Plugin $coregName updated!";
            } else {
                $message = "Coreg Plugin saved!";
                $coregPlugin = new CoregPlugin();
            }

            $coregPlugin->setName($request->get("name"));
            $coregPlugin->setIsVisible($request->get("is_visible") ?: null);
            $coregPlugin->setJustCollect($request->get("just_collect") ?: 0);
            $coregPlugin->setText($request->get("text"));
            $coregPlugin->setMonthlyCap($request->get("monthly_cap") ?: null);
            $coregPlugin->setDisplayPosition($request->get("display_position"));
            $this->em->persist($coregPlugin);
            $this->em->flush();

            $coregRequirementsRuleSet = $this->saveCoregRequirementsRuleSet($request);
            $coregRequirementsRuleSet->setCoreg($coregPlugin->getId());

            $this->em->flush();
            $this->em->clear();

        } catch (\Exception $exc) {
            \Log::error($exc);
            $message = "System error!";
        }
        return \Response::redirectToRoute($url, ['id' => $coregPlugin->getId()])->with([
            'message' => $message,
        ]);
    }

    /**
     * Update coreg's requirements rule set
     * @param Request $request
     *
     * @return CoregRequirementsRuleSet|null|object
     */
    protected function saveCoregRequirementsRuleSet(Request $request)
    {
        $coregRequirementsRuleSet = null;
        try {
            if ($ids = $request->get("redirect_rules_set_id", null)) {
                foreach ($ids as $id) {
                    if (!empty($id)) {
                        $coregRequirementsRuleSet = $this->coregRequirementsSetRepository->findById($id);
                        foreach ($coregRequirementsRuleSet->getCoregRequirementsRule() as $requirementsRule) {
                            $this->em->remove($requirementsRule);
                        }
                        $this->em->flush();
                    }
                    else {
                        $coregRequirementsRuleSet = new CoregRequirementsRuleSet();
                        $this->em->persist($coregRequirementsRuleSet);
                    }
                }
            }

            $coregRequirementsRuleSet->setType($request->get("type") ?: "AND");
            $coregRequirementsRuleSet->setTableName(RedirectRulesSet::TABLE);

            foreach ($request->get('requirements_rule') as $ruleGroup){
                foreach ($ruleGroup as $key => $rule) {
                    if (!in_array($rule['field'], array_keys($this->getProfileFields()))
                        || !in_array($rule['operator'],array_keys(CoregRequirementsRule::getCoregRequirementsRuleOperators()))
                    ) {
                        continue;
                    }
                    $coregRequirementsRule = new CoregRequirementsRule($rule['field'], $rule['operator'], $rule['value'], $rule['active'], $rule['send']);
                    $this->em->persist($coregRequirementsRule);
                    $coregRequirementsRule->setCoregRequirementsRuleSet($coregRequirementsRuleSet);
                }
            }
            $this->em->flush();

        } catch (\Exception $exc) {
            \Log::error($exc);
        }

        return $coregRequirementsRuleSet;
    }

    public function deleteCoregPluginAction(Request $request)
    {
        $model = new JsonModel();

        try {
            $coregPlugin = $this->coregRepository->findById($request->get("id"));
            $this->em->remove($coregPlugin);
            $this->em->flush();

            $model->setStatus(JsonModel::STATUS_OK);
        } catch (\Exception $exc) {
            $this->logError($exc);
            $model->setStatus(JsonModel::STATUS_ERROR);
        }

        return redirect()->route("admin::marketing.coregs.list");
    }

    /**
     * @param $profileFields []
     *
     * @return mixed
     */
    private function getProfileFields($profileFields = [])
    {
        foreach (\Schema::getColumnListing("profile") as $profileField) {
            $profileFields[$profileField] = $profileField;
        }

        foreach (CoregRequirementsRuleService::CUSTOM_FIELDS as $key => $value)
        {
            $profileFields[$key] = $value;
        }

        return $profileFields;
    }

}
