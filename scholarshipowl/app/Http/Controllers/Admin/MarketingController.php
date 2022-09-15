<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Domain;
use App\Entity\FeatureAbTest;
use App\Entity\Repository\EntityRepository;
use App\Entity\TransactionalEmail;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\Entity\Marketing\Affiliate;
use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoal;
use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoalMapping;
use App\Entity\Marketing\CoregPlugin;
use ScholarshipOwl\Data\Entity\Marketing\MarketingSystem;
use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use ScholarshipOwl\Data\Service\Marketing\AffiliateGoalMappingService;
use ScholarshipOwl\Data\Service\Marketing\AffiliateService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Util\Storage;


class MarketingController extends BaseController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubmissionService
     */
    protected $ss;

    /**
     * @var EntityRepository
     */
    protected $transactionalEmailRepo;

    /**
     * MarketingController constructor.
     *
     * @param EntityManager $em
     * @param SubmissionService $ss
     */
    public function __construct(EntityManager $em, SubmissionService $ss)
    {
        parent::__construct();

        $this->em = $em;
        $this->ss = $ss;
        $this->transactionalEmailRepo = $em->getRepository(TransactionalEmail::class);
    }

    public function indexAction()
    {
        $model = new ViewModel("admin/marketing/index");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing"
            ),
            "title" => "Marketing Systems",
            "active" => "marketing",
        );

        $model->setData($data);
        return $model->send();
    }

    /**
     * Affiliates Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function affiliatesAction()
    {
        $model = new ViewModel("admin/marketing/affiliates");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Affiliates" => "/admin/marketing/affiliates"
            ),
            "title" => "Affiliates",
            "active" => "marketing",
            "affiliates" => array(),
        );

        try {
            $service = new AffiliateService();
            $data["affiliates"] = $service->getAffiliates();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }


    /**
     * Add Affiliate Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function saveAffiliateAction()
    {
        $model = new ViewModel("admin/marketing/affiliates-save");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Affiliates" => "/admin/marketing/affiliates",
            ),
            "active" => "marketing",
            "affiliate" => new Affiliate(),
            "goal" => new AffiliateGoal(),
            "affiliate_path" => Storage::getAffiliatePath(),
            "options" => array(
                "active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
            ),
        );

        try {
            $service = new AffiliateService();

            $id = $this->getQueryParam("id");
            if (empty($id)) {
                $data["title"] = "Add Affiliate";
                $data["breadcrumb"]["Add Affiliate"] = "/admin/marketing/affiliates/save";
            } else {
                $affiliate = $service->getAffiliate($id, true);
                $goals = array_values($affiliate->getAffiliateGoals());

                if (!empty($goals)) {
                    $data["goal"] = $goals[0];
                }

                $data["title"] = $affiliate->getName();
                $data["affiliate"] = $affiliate;
                $data["breadcrumb"]["Edit Affiliate"] = "/admin/marketing/affiliates/save?id=$id";
            }

            $data["flash"] = $this->getFlashData();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }


    /**
     * Post Add Affiliate Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function postSaveAffiliateAction()
    {
        $redirectUrl = "/admin/marketing/affiliates/save";

        try {
            $input = $this->getAllInput();
            $errors = array();
            $logo = "";

            if (!empty($input["affiliate_id"])) {
                $affiliateId = $input["affiliate_id"];
                $redirectUrl .= "?id=$affiliateId";
            }

            $file = \Input::file("goal_logo");
            if (!empty($file)) {
                $name = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                if (!in_array($extension, array("jpg", "jpeg", "png"))) {
                    $errors["goal_logo"] = "Not a valid image file !";
                } else {
                    $logo = $name;
                }
            }

            if (empty($input["name"])) {
                $errors["name"] = "Name is empty !";
            }

            if (empty($input["is_active"]) && $input["is_active"] != "0") {
                $errors["is_active"] = "Is active is empty !";
            }

            if (empty($input["goal_name"])) {
                $errors["goal_name"] = "Goal name is empty !";
            }

            if (empty($input["goal_url"])) {
                $errors["goal_url"] = "Goal url is empty !";
            } else {
                if (filter_var($input["goal_url"], FILTER_VALIDATE_URL) === false) {
                    $errors["goal_url"] = "Goal url not valid !";
                }
            }

            if (empty($errors)) {
                $service = new AffiliateService();

                $affiliate = new Affiliate();
                $affiliate->populate($input);

                $affiliateGoal = new AffiliateGoal();
                $affiliateGoal->setAffiliateGoalId($input["affiliate_goal_id"]);
                $affiliateGoal->setName($input["goal_name"]);
                $affiliateGoal->setValue($input["goal_value"]);
                $affiliateGoal->setUrl($input["goal_url"]);
                $affiliateGoal->setDescription($input["goal_description"]);
                $affiliateGoal->setRedirectDescription($input["goal_redirect_description"]);
                $affiliateGoal->setRedirectTime($input["goal_redirect_time"]);

                if (!empty($logo)) {
                    $logo = md5(time()) . "_" . $logo;
                    $path = Storage::getAffiliatePath();

                    \Input::file("goal_logo")->move($path, $logo);
                    $affiliateGoal->setLogo($logo);
                }

                if (empty($input["affiliate_id"])) {
                    $affiliateId = $service->addAffiliate($affiliate, array($affiliateGoal));

                    $input["affiliate_id"] = $affiliateId;
                    $redirectUrl .= "?id=$affiliateId";
                } else {
                    $service->updateAffiliate($affiliate, array($affiliateGoal));
                }

                $this->setFlashData("Affiliate saved !", "ok");
            } else {
                $this->setFlashData($errors, "error");
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
            $this->setFlashData(array("System error !"), "error");
        }

        return $this->redirect($redirectUrl);
    }


    /**
     * Affiliates Responses Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function affiliatesResponsesAction()
    {
        $model = new ViewModel("admin/marketing/affiliates_responses");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Affiliates" => "/admin/marketing/affiliates",
                "Affiliates Responses" => "/admin/marketing/affiliates_responses"
            ),
            "title" => "Affiliates Responses",
            "active" => "marketing",
            "responses" => array(),
            "has_offers" => array(),
            "count" => 0,
            "search" => array(
                "first_name" => "",
                "last_name" => "",
                "affiliate_name" => "",
                "response_date_from" => "",
                "response_date_to" => "",
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/marketing/affiliates_responses",
                "url_params" => array()
            ),
        );

        try {
            $service = new AffiliateService();
            $marketingSystemService = new MarketingSystemService();

            $display = 50;
            $pagination = $this->getPagination($display);

            $input = $this->getAllInput();
            unset($input["page"]);
            foreach ($input as $key => $value) {
                $data["search"][$key] = $value;
            }

            $searchResult = $service->searchResponses($data["search"], $pagination["limit"]);

            $accountIds = array();
            foreach ($searchResult["data"] as $affiliateGoalResponseId => $row) {
                $accountIds[] = $row["account_id"];
            }
            $accountIds = array_unique($accountIds);

            if (!empty($accountIds)) {
                $data["has_offers"] = $marketingSystemService->getMarketingSystemParametersByAccountIds($accountIds);
            }


            $data["responses"] = $searchResult["data"];
            $data["count"] = $searchResult["count"];
            $data["pagination"]["page"] = $pagination["page"];
            $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
            $data["pagination"]["url_params"] = $data["search"];
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }


    /**
     * Submissions Search Action
     *
     * @access public
     *
     * @param Request $request
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function submissionsAction(Request $request)
    {
        $model = new ViewModel("admin/marketing/submissions");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Submissions" => "/admin/marketing/submissions",
            ),
            "title" => "Submissions",
            "active" => "marketing",
            "submissions" => array(),
            "count" => 0,
            "search" => array(
                "name" => array(),
                "status" => array(),
                "send_date_from" => "",
                "send_date_to" => "",
            ),
            "options" => array(
                "names" => Submission::getNames(),
                "statuses" => Submission::getStatuses(),
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/marketing/submissions",
                "url_params" => array()
            ),
        );

        try {
            $display = 50;
            $page = $request->get("page", 1);

            $input = $this->getAllInput();
            unset($input["page"]);
            foreach ($input as $key => $value) {
                $data["search"][$key] = $value;
            }

            $searchResult = $this->ss->searchSubmissions($data["search"], [$page - 1, $display]);

            $data["submissions"] = $searchResult;
            $data["count"] = count($searchResult);
            $data["pagination"]["page"] = $page;
            $data["pagination"]["pages"] = ceil($data["count"] / $display);
            $data["pagination"]["url_params"] = $data["search"];
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        $model->setData($data);
        return $model->send();
    }


    public function searchAction()
    {
        $model = new ViewModel("admin/marketing/search");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Search Marketing" => "/admin/marketing/search"
            ),
            "title" => "Search Marketing",
            "active" => "marketing",
            "data" => array(),
            "count" => 0,
            "search" => array(
                "marketing_system_id" => "",
                "conversion_date_from" => "",
                "conversion_date_to" => "",
                "transaction_id" => "",
                "offer_id" => "",
                "affiliate_id" => "",
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/marketing/search",
                "url_params" => array(),
            ),
            "options" => array(
                "marketing_systems" => MarketingSystem::getMarketingSystemNames(),
            ),
        );

        try {
            $marketingSystemService = new MarketingSystemService();
            $input = $this->getAllInput();

            $display = 50;
            $pagination = $this->getPagination($display);

            unset($input["page"]);
            foreach ($input as $key => $value) {
                $data["search"][$key] = $value;
            }

            $searchResult = $marketingSystemService->search($data["search"], $pagination["limit"]);

            $data["data"] = $searchResult["data"];
            $data["count"] = $searchResult["count"];
            $data["pagination"]["page"] = $pagination["page"];
            $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
            $data["pagination"]["url_params"] = $data["search"];
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    public function affiliateGoalMappingAction()
    {
        $model = new ViewModel("admin/marketing/affiliate_goal_mapping");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Affiliate Goal Mapping" => "/admin/marketing/affiliate_goal_mapping"
            ),
            "title" => "Affiliate Goal Mapping",
            "active" => "marketing",
            "affiliate_goal_mappings" => array(),
        );

        try {
            $service = new AffiliateGoalMappingService();
            $affiliateGoalMappings = $service->getAffiliateGoalMappings();

            foreach ($affiliateGoalMappings as $affiliateGoalMapping) {
                $redirectRulesSet = $this->em->getRepository(RedirectRulesSet::class)->findOneBy(['id' => $affiliateGoalMapping->getRedirectRulesSetId()]);
                if ($redirectRulesSet) {
                    $affiliateGoalMapping->redirectRulesSetName = $redirectRulesSet->getName();
                } else {
                    $affiliateGoalMapping->redirectRulesSetName = "Untitled";
                }

            }
            $data["affiliate_goal_mappings"] = $affiliateGoalMappings;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    /**
     * Save Affiliate Goal Mapping Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function saveAffiliateGoalMappingAction()
    {
        $model = new ViewModel("admin/marketing/affiliate_goal_mapping_save");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Affiliate Goal Mapping" => "/admin/marketing/affiliate_goal_mapping"
            ),
            "title" => "Affiliate Goal Mapping",
            "active" => "marketing",
            "affiliate_goal_mapping" => new AffiliateGoalMapping(),
            "options" => array(
                "active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "redirect_rules" => array()
            ),
        );

        try {
            $id = $this->getQueryParam("id");
            if (empty($id)) {
                $data["title"] = "Add Affiliate";
                $data["breadcrumb"]["Add Affiliate Goal Mapping"] = "/admin/marketing/affiliate_goal_mapping/save";
            } else {
                $service = new AffiliateGoalMappingService();
                $affiliateGoalMapping = $service->getAffiliateGoalMapping($id);

                $data["affiliate_goal_mapping"] = $affiliateGoalMapping;
                $data["breadcrumb"]["Edit Affiliate Goal Mapping"] = "/admin/marketing/affiliate_goal_mapping/save?id=$id";
            }

            $redirectRulesService = new RedirectRulesService();

            $redirectRules = $redirectRulesService->getRedirectRulesSets();
            foreach ($redirectRules as $key => $redirectRule) {
                $data["options"]["redirect_rules"][$key] = $redirectRule->getName();
            }


            $data["flash"] = $this->getFlashData();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    /**
     * Post Save Affiliate Goal Mapping Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function postSaveAffiliateGoalMappingAction()
    {
        $model = new JsonModel();

        try {
            $input = $this->getAllInput();
            $errors = array();
            $pages = array();

            if (empty($input["url_parameter"])) {
                $errors["url_parameter"] = "URL Parameter is empty!";
            }

            if (empty($input["affiliate_goal_id"])) {
                $errors["affiliate_goal_id"] = "Goal ID is empty!";
            }

            if (empty($input["affiliate_goal_id_secondary"])) {
                $errors["affiliate_goal_id_secondary"] = "Secondary goal ID is empty!";
            }

            if (empty($input["redirect_rules_set_id"])) {
                $errors["redirect_rules_set_id"] = "Redirect rules set is empty!";
            }

            if (empty($errors)) {
                $service = new AffiliateGoalMappingService();

                $affiliateGoalMapping = new AffiliateGoalMapping();

                $affiliateGoalMapping->populate($input);

                if (empty($input["affiliate_goal_mapping_id"])) {
                    $affiliateGoalMappingId = $service->addAffiliateGoalMapping($affiliateGoalMapping);

                    $model->setStatus(JsonModel::STATUS_REDIRECT);
                    $model->setMessage("Affiliate Goal Mapping saved !");
                    $model->setData("/admin/marketing/affiliate_goal_mapping");
                } else {
                    $service->updateAffiliateGoalMapping($affiliateGoalMapping);

                    $model->setStatus(JsonModel::STATUS_OK);
                    $model->setMessage("Affiliate Goal Mapping !");
                }
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }

    public function deleteAffiliateGoalMappingAction()
    {
        $model = new JsonModel();

        try {
            $service = new AffiliateGoalMappingService();
            $input = $this->getAllInput();
            $service->deleteAffiliateGoalMapping($input["id"]);

            $model->setStatus(JsonModel::STATUS_OK);
        } catch (\Exception $exc) {
            $this->logError($exc);
            $model->setStatus(JsonModel::STATUS_ERROR);
        }
        return $this->redirect("/admin/marketing/affiliate_goal_mapping");
    }

    /**
     * Redirect Rules Set Action - List all redirect rule sets
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function redirectRulesSetAction()
    {
        $model = new ViewModel("admin/marketing/redirect_rules_set");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Redirect Rules Sets" => "/admin/marketing/redirect_rules_set"
            ),
            "title" => "Redirect Rules Sets",
            "active" => "marketing",
            "redirect_rules_sets" => array(),
            "flash" => array()
        );

        try {
            $data["redirect_rules_sets"] = \EntityManager::getRepository("App\Entity\Marketing\RedirectRulesSet")->findAll();

            $data["flash"] = $this->getFlashData();
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        $model->setData($data);
        return $model->send();
    }

    /**
     * Save Redirect Rules Set Action
     *
     * @access public
     * @param Request $request
     * @return Response
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function saveRedirectRulesSetAction(Request $request)
    {
        $model = new ViewModel("admin/marketing/redirect_rules_set_save");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Marketing" => "/admin/marketing",
                "Redirect Rules Set" => "/admin/marketing/redirect_rules_set"
            ),
            "title" => "Redirect Rules Set",
            "active" => "marketing",
            "redirect_rules_set" => new RedirectRulesSet(),
            "options" => array(
                "rule_types" => array("" => "--- Select ---") + RedirectRulesSet::getRedirectRulesSetTypes(),
                "profile_fields" => array("" => "--- Select ---"),
                "operators" => array("" => "--- Select ---") + RedirectRule::getRedirectRuleOperatorTypes()
            ),
        );

        try {
            $id = $request->get("id");
            $service = new RedirectRulesService();

            if (empty($id)) {
                $data["title"] = "Add Redirect Rules Set";
                $data["breadcrumb"]["Add Redirect Rules Set"] = "/admin/marketing/redirect_rules_set/save";
            } else {
                $redirectRulesSet = \EntityManager::getRepository("App\Entity\Marketing\RedirectRulesSet")->findById($id);

                $data["redirect_rules_set"] = $redirectRulesSet;
                $data["breadcrumb"]["Edit Redirect Rules Set"] = "/admin/marketing/redirect_rules_set/save?id=$id";
            }

            $profileFields = array();
            foreach (\Schema::getColumnListing("profile") as $profileField) {
                $profileFields[$profileField] = $profileField;
            }

            foreach ($service->getCustomFields() as $key => $value) {
                $profileFields[$key] = $value;
            }

            $data["options"]["profile_fields"] += $profileFields;

            $data["flash"] = $this->getFlashData();
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    /**
     * Post Save Redirect Rules Set Action
     *
     * @access public
     *
     * @param Request $request
     *
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function postSaveRedirectRulesSetAction(Request $request)
    {
        $model = new JsonModel();

        try {
            $errors = array();

            foreach ($request->all() as $key => $value) {
                if (strpos($key, "redirect_rule_field_") !== false) {
                    $ruleId = str_replace("redirect_rule_field_", "", $key);
                    $fieldInput = sprintf("redirect_rule_field_%s", $ruleId);
                    $operatorInput = sprintf("redirect_rule_operator_%s", $ruleId);
                    $valueInput = sprintf("redirect_rule_value_%s", $ruleId);

                    if (!$request->get($fieldInput) || !$request->get($operatorInput) || $request->get($valueInput) === null) {
                        $errors["redirect_rule_field_" . $ruleId] = "Incomplete rules can't be saved !";
                    }
                }
            }

            if (empty($errors)) {
                $id = $request->get("redirect_rules_set_id", null);

                if ($id) {
                    $redirectRulesSet = \EntityManager::getRepository("App\Entity\Marketing\RedirectRulesSet")->findById($id);
                } else {
                    $redirectRulesSet = new RedirectRulesSet();
                    \EntityManager::persist($redirectRulesSet);
                }

                $redirectRulesSet->setName($request->get("name"));
                $redirectRulesSet->setType($request->get("type") ?: "AND");
                $redirectRulesSet->setTableName(RedirectRulesSet::TABLE);

                if (!$id) {
                    $model->setStatus(JsonModel::STATUS_REDIRECT);
                    $model->setMessage("Redirect Rules Set saved !");
                    $model->setData("/admin/marketing/redirect_rules_set");
                } else {
                    foreach ($redirectRulesSet->getRedirectRules() as $redirectRule) {
                        \EntityManager::remove($redirectRule);
                    }

                    $model->setStatus(JsonModel::STATUS_OK);
                    $model->setMessage("Redirect Rules Set updated !");
                }

                foreach ($request->all() as $key => $value) {
                    if (strpos($key, "redirect_rule_field_") !== false) {
                        $ruleId = str_replace("redirect_rule_field_", "", $key);
                        $fieldInput = sprintf("redirect_rule_field_%s", $ruleId);
                        $operatorInput = sprintf("redirect_rule_operator_%s", $ruleId);
                        $valueInput = sprintf("redirect_rule_value_%s", $ruleId);
                        $activeInput = sprintf("redirect_rule_active_%s", $ruleId);

                        $redirectRule = new RedirectRule($request->get($fieldInput), $request->get($operatorInput), $request->get($valueInput));
                        \EntityManager::persist($redirectRule);
                        $redirectRule->setRedirectRulesSet($redirectRulesSet);

                        if (!empty($request->get($activeInput))) {
                            $redirectRule->setActive(1);
                        } else {
                            $redirectRule->setActive(0);
                        }
                    }
                }

                \EntityManager::flush();
            } else {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $model->setMessage("Please fix errors !");
                $model->setData($errors);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);

            $model->setStatus(JsonModel::STATUS_ERROR);
            $model->setMessage("System error !");
        }

        return $model->send();
    }

    /**
     * Delete Redirect Rules Set Action
     *
     * @access public
     * @param Request $request
     * @return Response
     * @author Ivan Krkoitc <ivan.krkotic@gmail.com>
     */
    public function deleteRedirectRulesSetAction(Request $request)
    {
        $model = new JsonModel();

        try {
            $redirectRulesSet = \EntityManager::getRepository("App\Entity\Marketing\RedirectRulesSet")->findById($request->get("id"));
            \EntityManager::remove($redirectRulesSet);

            try {
                foreach ($redirectRulesSet->getRedirectRules() as $redirectRule) {
                    \EntityManager::remove($redirectRule);
                }

                \EntityManager::flush();
            } catch (\Exception $exc) {
                $model->setStatus(JsonModel::STATUS_ERROR);
                $this->setFlashData(array("Redirect rule used in mapping cannot be deleted !"), "error");
            }

            $model->setStatus(JsonModel::STATUS_OK);
        } catch (\Exception $exc) {
            $this->logError($exc);
            $model->setStatus(JsonModel::STATUS_ERROR);
        }

        return $this->redirect("/admin/marketing/redirect_rules_set");
    }

    /**
     * Delete Redirect Rule Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkoitc <ivan.krkotic@gmail.com>
     */
    public function deleteRedirectRuleAction($redirectRuleId)
    {
        $model = new JsonModel();

        try {
            $service = new RedirectRulesService();
            $service->deleteRedirectRule($redirectRuleId);

            $model->setStatus(JsonModel::STATUS_OK);
        } catch (\Exception $exc) {
            $this->logError($exc);
            $model->setStatus(JsonModel::STATUS_ERROR);
        }

        return $model->send();
    }
}
