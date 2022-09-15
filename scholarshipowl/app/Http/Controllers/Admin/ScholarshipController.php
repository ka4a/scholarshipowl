<?php namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\Admin\Admin;
use App\Entity\Application;
use App\Entity\ApplicationFile;
use App\Entity\ApplicationImage;
use App\Entity\ApplicationInput;
use App\Entity\ApplicationSpecialEligibility;
use App\Entity\ApplicationSurvey;
use App\Entity\ApplicationText;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\RequirementFile;
use App\Entity\RequirementImage;
use App\Entity\RequirementInput;
use App\Entity\RequirementName;
use App\Entity\RequirementSpecialEligibility;
use App\Entity\RequirementSurvey;
use App\Entity\RequirementText;
use App\Entity\Scholarship as ScholarshipEntity;

use App\Entity\ScholarshipStatus;
use App\Entity\SuperCollegeScholarship;
use App\Events\Scholarship\ScholarshipDeletedEvent;
use App\Events\Scholarship\ScholarshipUpdatedEvent;
use App\Services\ApplicationService\ApplicationSenderEmail;
use App\Services\ApplicationService\ApplicationSenderOnline;
use App\Services\ApplicationService\ApplicationSenderSunrise;
use App\Services\EligibilityService;
use App\Services\SuperCollege\SuperCollegeService;
use App\Traits\SunriseSync;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Validation\Rule;
use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Info\Field;
use ScholarshipOwl\Data\Entity\Scholarship\Eligibility;
use ScholarshipOwl\Data\Entity\Scholarship\Form;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Scholarship\SearchService as ScholarshipSearchService;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;
use ScholarshipOwl\Data\Service\Scholarship\StatisticService as ScholarshipStatisticService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Vendor\SimpleHtmlDom\Factory as DomFactory;
use App\Services\SuperCollege\Types as SuperCollegeTypes;
use \Curl\Curl;

use Illuminate\Http\Request;

/**
 * Scholarship Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ScholarshipController extends BaseController
{

    use SunriseSync;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
    * @var \App\Services\ScholarshipService
    */
    protected $service;

    /**
     * ScholarshipController constructor.
     *
     * @param EntityManager                    $em
     * @param \App\Services\ScholarshipService $service
     */
    public function __construct(EntityManager $em, \App\Services\ScholarshipService $service)
    {
        parent::__construct();

        $this->em = $em;
        $this->service = $service;
        $this->repository = $em->getRepository(ScholarshipEntity::class);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveMetatagsAction(Request $request, $id)
    {
        /** @var \App\Entity\Scholarship $scholarship */
        $scholarship = $this->repository->findById($id);
        $scholarship->setMetaAuthor($request->get('meta_author', ''));
        $scholarship->setMetaTitle($request->get('meta_title', ''));
        $scholarship->setMetaDescription($request->get('meta_description', ''));
        $scholarship->setMetaKeywords($request->get('meta_keywords', ''));

        $this->em->flush();

        return \Redirect::to(\URL::previous())->with([
            'message' => 'Meta tags saved!',
        ]);
    }

    /**
     * @return mixed
     */
    public function maintainAction()
    {
        list($activated, $deactivated, $recurred) = $this->service->maintain();

        return redirect(route('admin::scholarships.search'))
            ->with(['message' => sprintf(
                'Scholarships maintained: activated %s, deactivated %s, recurred %s',
                $activated,
                $deactivated,
                $recurred
            )]);
    }

    /**
	 * Scholarships Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/scholarships/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
			),
			"title" => "Scholarships",
			"active" => "scholarships"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Search Sholarships Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$model = new ViewModel("admin/scholarships/search");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
				"Search Scholarships" => "/admin/scholarships/search"
			),
			"title" => "Search Scholarships",
			"active" => "scholarships",
			"scholarships" => array(),
			"count" => 0,
			"applied_counts" => array(),
			"search" => array(
				"title" => "",
                "status" => "",
				"expiration_date_from" => "",
				"expiration_date_to" => "",
				"amount_min" => "",
				"amount_max" => "",
				"up_to_min" => "",
				"up_to_max" => "",
				"application_type" => "",
				"is_active" => "",
				"is_free" => "",
				"is_recurrent" => "",
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/scholarships/search",
				"url_params" => array()
			),
			"options" => array(
                "status" => [null => '--- Select ---'] + ScholarshipStatus::options(),
				"application_types" => array("" => "--- Select ---") + Scholarship::getApplicationTypes(),
				"form_methods" => Scholarship::getFormMethods(),
				"active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"free" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"recurrent" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
			),
            'scholarshipEntities' => [],
		);

		try {
			$searchService = new ScholarshipSearchService();
			$statisticService = new ScholarshipStatisticService();

			$display = 20;
			$pagination = $this->getPagination($display);

			$input = $this->getAllInput();
			unset($input["page"]);
			foreach($input as $key => $value) {
				$data["search"][$key] = $value;
			}

			$searchResult = $searchService->searchScholarships($data["search"], $pagination["limit"]);

			if(!empty($searchResult["data"])) {
				$scholarshipIds = array_keys($searchResult["data"]);

				$data["applied_counts"] = $statisticService->getApplicationsCountByScholarshipIds($scholarshipIds);

                /** @var ScholarshipEntity[] $scholarshipEntities */
                $scholarshipEntities = \EntityManager::getRepository(ScholarshipEntity::class)->findBy([
                    'scholarshipId' => $scholarshipIds
                ]);

                foreach ($scholarshipEntities as $scholarshipEntity) {
                    $data['scholarshipEntities'][$scholarshipEntity->getScholarshipId()] = $scholarshipEntity;
                }
			}

			$data["scholarships"] = $searchResult["data"];
			$data["count"] = $searchResult["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
			$data["pagination"]["url_params"] = $data["search"];
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}



	/**
	 * View Scholarship Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function viewAction() {
		$model = new ViewModel("admin/scholarships/view");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
				"Search Scholarships" => "/admin/scholarships/search"
			),
			"title" => "Search Scholarships",
			"active" => "scholarships",
			"scholarship" => new Scholarship(),
			"eligibility_types" => Eligibility::getTypes(),
			"fields" => InfoServiceFactory::get("Field")->getAll(false),
			"multi_values" => Field::getMultiValues()
		);

		try {
			$service = new ScholarshipService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				if (!$scholarship = $service->getScholarship($id)) {
                    return redirect()->route('admin::scholarships.search')
                        ->with('error', "Scholarship with id [ ${id} ] not found");
                }
				$eligibilities = $service->getScholarshipEligibilities($id);
				$forms = $service->getForm($id);

				$scholarship->setEligibilities($eligibilities);
				$scholarship->setForms($forms);

				$data["title"] = $scholarship->getTitle();
				$data["scholarship"] = $scholarship;
                $data['scholarshipEntity'] = $this->repository->find($scholarship->getScholarshipId());
				$data["breadcrumb"]["View Scholarship"] = "/admin/scholarships/view?id=$id";
			}
			else {
				throw new \Exception("Scholarship id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Save Scholarship Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveAction() {
		$model = new ViewModel("admin/scholarships/save");
        $americaTZ = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL_WITH_BC);
        $hours = range(0, 23);
        $minutes = range(0, 59);

        array_walk($hours, function (&$v, $k) {
            $v = sprintf('%02d', $v);
        });

        array_walk($minutes, function (&$v, $k) {
            $v = sprintf('%02d', $v);
        });

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
				"Search Scholarships" => "/admin/scholarships/search"
			),
			"active" => "scholarships",
			"scholarship" => new Scholarship(),
			"fields" => array(),
			"options" => array(
				"application_types" => array("" => "--- Select ---") + Scholarship::getApplicationTypes(),
				"form_methods" => Scholarship::getFormMethods(),
				"active" => array("1" => "Yes", "0" => "No"),
				"free" => array("1" => "Yes", "0" => "No"),
				"eligibility_types" => Eligibility::getTypes(),
				"gender" => array("0" => "---") + Profile::getGenders(),
				"citizenship" => array("0" => "---") + InfoServiceFactory::get("Citizenship")->getAll(),
				"ethnicity" => array("0" => "---") + InfoServiceFactory::get("Ethnicity")->getAll(),
				"country" => array("0" => "---") + InfoServiceFactory::get("Country")->getAll(),
                "studycountry" => array("0" => "---") + InfoServiceFactory::get("Country")->getAll(),
				"state" => array("0" => "---") + InfoServiceFactory::get("State")->getAll(),
				"schoollevel" => array("0" => "---") + InfoServiceFactory::get("SchoolLevel")->getAll(),
				"degree" => array("0" => "---") + InfoServiceFactory::get("Degree")->getAll(),
				"degreetype" => array("0" => "---") + InfoServiceFactory::get("DegreeType")->getAll(),
				"militaryaffiliation" => InfoServiceFactory::get("MilitaryAffiliation")->getAll(),
				"gpa" => array("0" => "---") + Profile::getGpaArray(),
				"multi_values" => Field::getMultiValues(),
				"files_alowed" => array("1" => "Yes", "0" => "No"),
				"automatic" => array("1" => "Yes", "0" => "No"),
                "send_to_private" => array("1" => "Yes", "0" => "No"),
                "is_recurrent" => array("1" => "Yes", "0" => "No"),
                'status' => ScholarshipStatus::options(),
                'timezones' => array_combine($americaTZ, $americaTZ),
                'hours' => array_combine($hours, $hours),
                'minutes' => array_combine($minutes, $minutes),
			),
			"online_data" => array(),
            'requirementFileNames' => RequirementName::options(['type' => RequirementName::TYPE_FILE]),
            'requirementImageNames' => RequirementName::options(['type' => RequirementName::TYPE_IMAGE]),
            'requirementTextNames' => RequirementName::options(['type' => RequirementName::TYPE_TEXT]),
            'requirementInputNames' => RequirementName::options(['type' => RequirementName::TYPE_INPUT]),
            'requirementSurveyNames' => RequirementName::options(['type' => RequirementName::TYPE_SURVEY]),
            'requirementSpecialEligibilityNames' => RequirementName::options(['type' => RequirementName::TYPE_SPECIAL_ELIGIBILITY]),
		);

        $scholarshipService = new ScholarshipService();

        $id = $this->getQueryParam("id");
        if(empty($id)) {
            $data["title"] = "Add Scholarship";
            $data["fields"] = array("0" => "--- Select Field ---") + InfoServiceFactory::get("Field")->getAll(false);
            $data["breadcrumb"]["Add Scholarship"] = "/admin/scholarships/save";
        }
        else {
            if (!$scholarship = $scholarshipService->getScholarship($id)) {
                return redirect()->route('admin::scholarships.search')
                    ->with('error', "Scholarship with id [ ${id} ] not found");
            }

            $scholarship->setEligibilities($scholarshipService->getScholarshipEligibilities($id));

            /** @var ScholarshipEntity $scholarshipEntity */
            $scholarshipEntity = \EntityManager::findById(ScholarshipEntity::class, $id);

            $forms = $scholarshipService->getForm($id);
            foreach ($forms as $form) {
                $data["online_data"][$form->getFormField()] = $form->toArray();
            }

            $title = $scholarship->getTitle();
            if ($scholarship->getApplicationType() === ScholarshipEntity::APPLICATION_TYPE_SUNRISE) {
                $title .= ' <span class="translucent">(sunrise)</span>';
            }
            $data["title"] = $title;
            $data["scholarship"] = $scholarship;
            $data["fields"] = array("0" => "--- Select Field ---") + InfoServiceFactory::get("Field")->getAll(false);
            $data["breadcrumb"]["Edit Scholarship"] = "/admin/scholarships/save?id=$id";
            $data['scholarshipEntity'] = $scholarshipEntity;
        }

		$model->setData($data);

		return $model->send();
	}

    /**
     * Process and set optional flag for requirements
     * @param Request $request
     * @param $requirementUpdatedArray array of updates requirement entities
     */
	protected function setIsOptionalRequirement(Request $request, $requirementUpdatedArray)
    {
        foreach ($requirementUpdatedArray as $requirementUpdated) {
            foreach ($requirementUpdated as $key => $item) {
                $requirementType = $item->getType();
                $requirementData = $request->get('requirement_' . $requirementType);
                if (isset($requirementData[$key]) && isset($requirementData[$key]['isOptional'])) {
                    $item->setIsOptional($requirementData[$key]['isOptional']);
                }
            }
        }
    }

    public function saveRequirements(Request $request)
    {
        $this->validate($request, [
            'scholarship_id' => 'required|entity:Scholarship',
            'requirement_text.*.id' => 'numeric|entity:RequirementText',
            'requirement_text.*.requirementName' => 'required|entity:RequirementName',
            'requirement_text.*.title' => 'string',
            'requirement_text.*.description' => 'required|string',
            'requirement_text.*.sendType' => 'required|string',
            'requirement_text.*.attachmentType' => 'string',
            'requirement_text.*.attachmentFormat' => 'string',
            'requirement_text.*.allowFile' => 'numeric',
            'requirement_text.*.fileExtension' => 'string',
            'requirement_text.*.maxFileSize' => 'numeric',
            'requirement_text.*.minWords' => 'numeric',
            'requirement_text.*.maxWords' => 'numeric',
            'requirement_text.*.minCharacters' => 'numeric',
            'requirement_text.*.maxCharacters' => 'numeric',
            'requirement_file.*.id' => 'numeric|entity:RequirementFile',
            'requirement_file.*.requirementName' => 'required|entity:RequirementName',
            'requirement_file.*.title' => 'string',
            'requirement_file.*.description' => 'required|string',
            'requirement_file.*.fileExtension' => 'string',
            'requirement_file.*.maxFileSize' => 'numeric',
            'requirement_image.*.id' => 'numeric|entity:RequirementImage',
            'requirement_image.*.requirementName' => 'required|entity:RequirementName',
            'requirement_image.*.title' => 'string',
            'requirement_image.*.description' => 'required|string',
            'requirement_image.*.fileExtension' => 'string',
            'requirement_image.*.maxFileSize' => 'numeric',
            'requirement_image.*.minWidth' => 'numeric',
            'requirement_image.*.maxWidth' => 'numeric',
            'requirement_image.*.minHeight' => 'numeric',
            'requirement_image.*.maxHeight' => 'numeric',
            'requirement_input.*.id' => 'numeric|entity:RequirementInput',
            'requirement_input.*.requirementName' => 'required|entity:RequirementName',
            'requirement_input.*.title' => 'string',
            'requirement_input.*.description' => 'required|string',
            'requirement_survey.*.survey' => 'required',
            'requirement_special_eligibility.*.id' => 'numeric|entity:RequirementSpecialEligibility',
            'requirement_special_eligibility.*.requirementName' => 'required|entity:RequirementName',
            'requirement_special_eligibility.*.title' => 'string',
            'requirement_special_eligibility.*.description' => 'required|string',
            'requirement_special_eligibility.*.text' => 'required|string|max:200',
        ], [

            'requirement_survey.*.survey.required' => 'The survey requirement should have at least 1 question',
            'requirement_survey.survey.*.options.*.required' => 'The survey requirement should have at least 1 suggested answers/options',
        ]);

        /** @var ScholarshipEntity $scholarship */
        $scholarship = \EntityManager::findById(ScholarshipEntity::class, $request->get('scholarship_id'));
        $requirementTextUpdated = new ArrayCollection();
        $requirementFileUpdated = new ArrayCollection();
        $requirementImageUpdated = new ArrayCollection();
        $requirementInputUpdated = new ArrayCollection();
        $requirementSpecialEligibilityUpdated = new ArrayCollection();
        $requirementSurveyUpdated = new ArrayCollection();

        $permanentTags = [];

        foreach ((array) $request->get('requirement_text') as $requirementTextInput) {
            foreach (['maxFileSize', 'minWords', 'maxWords', 'minCharacters', 'maxCharacters'] as $key) {
                if (isset($requirementTextInput[$key]) && $requirementTextInput[$key] === '') {
                    $requirementTextInput[$key] = null;
                }
            }

            if (!$requirementTextInput['permanentTag']) {
                $requirementTextInput['permanentTag'] = substr(uniqid(), 0, 8);
            } else {
                $requirementTextInput['permanentTag'] =
                    preg_replace("/[^a-zA-Z0-9_-]/", '_', $requirementTextInput['permanentTag']);
            }
            $permanentTags[] = $requirementTextInput['permanentTag'];

            if ($requirementTextInput['id']) {
                /** @var RequirementText $requirementText */
                $requirementText = \EntityManager::findById(RequirementText::class, $requirementTextInput['id']);
                unset($requirementTextInput['id']);
                $requirementText->hydrate($requirementTextInput);

            } else {
                $scholarship->addRequirementText($requirementText = new RequirementText($requirementTextInput));
            }

            $requirementTextUpdated->add($requirementText);

        }

        foreach ($scholarship->getRequirementTexts() as $requirementText) {
            if (!$requirementTextUpdated->contains($requirementText)) {
                $scholarship->removeRequirementText($requirementText);
            }
        }

        foreach ((array) $request->get('requirement_special_eligibility') as $requirementSpecEliInpute) {

            if (!$requirementSpecEliInpute['permanentTag']) {
                $requirementSpecEliInpute['permanentTag'] = substr(uniqid(), 0, 8);
            } else {
                $requirementSpecEliInpute['permanentTag'] =
                    preg_replace("/[^a-zA-Z0-9_-]/", '_', $requirementSpecEliInpute['permanentTag']);
            }
            $permanentTags[] = $requirementSpecEliInpute['permanentTag'];


            if ($requirementSpecEliInpute['id']) {
                /** @var RequirementSpecialEligibility $requirementSpecEli */
                $requirementSpecEli = \EntityManager::findById(RequirementSpecialEligibility::class, $requirementSpecEliInpute['id']);
                unset($requirementSpecEliInpute['id']);
                $requirementSpecEli->hydrate($requirementSpecEliInpute);

            } else {
                $scholarship->addRequirementSpecialEligibility($requirementSpecEli = new RequirementSpecialEligibility($requirementSpecEliInpute));
            }

            $requirementSpecialEligibilityUpdated->add($requirementSpecEli);

        }

        foreach ($scholarship->getRequirementSpecialEligibility() as $requirementSpecEli) {
            if (!$requirementSpecialEligibilityUpdated->contains($requirementSpecEli)) {
                $scholarship->removeRequirementSpecialEligibility($requirementSpecEli);
            }
        }

        foreach ((array) $request->get('requirement_file') as $requirementFileInput) {
            if ($requirementFileInput['id']) {
                /** @var RequirementFile $requirementFile */
                $requirementFile = \EntityManager::findById(RequirementFile::class, $requirementFileInput['id']);
                $requirementFile->setRequirementName($requirementFileInput['requirementName']);
                $requirementFile->setTitle($requirementFileInput['title']);
                $requirementFile->setDescription($requirementFileInput['description']);
                $requirementFile->setFileExtension($requirementFileInput['fileExtension'] ?: null);
                $requirementFile->setMaxFileSize($requirementFileInput['maxFileSize'] ?: null);
            } else {
                $scholarship->addRequirementFile($requirementFile = new RequirementFile(
                    $requirementFileInput['requirementName'],
                    $requirementFileInput['title'],
                    $requirementFileInput['description'],
                    $requirementFileInput['fileExtension'] ?: null,
                    $requirementFileInput['maxFileSize'] ?: null
                ));
            }

            $requirementFileUpdated->add($requirementFile);
        }

        foreach ($scholarship->getRequirementFiles() as $requirementFile) {
            if (!$requirementFileUpdated->contains($requirementFile)) {
                $scholarship->removeRequirementFile($requirementFile);
            }
        }

        foreach ((array) $request->get('requirement_image') as $requirementImageInput) {
            if ($requirementImageInput['id']) {
                /** @var RequirementImage $requirementImage */
                $requirementImage = \EntityManager::findById(RequirementImage::class, $requirementImageInput['id']);
                $requirementImage->setRequirementName($requirementImageInput['requirementName']);
                $requirementImage->setTitle($requirementImageInput['title']);
                $requirementImage->setDescription($requirementImageInput['description']);
                $requirementImage->setFileExtension($requirementImageInput['fileExtension']);
                $requirementImage->setMaxFileSize($requirementImageInput['maxFileSize']);
                $requirementImage->setMinWidth($requirementImageInput['minWidth']);
                $requirementImage->setMaxWidth($requirementImageInput['maxWidth']);
                $requirementImage->setMinHeight($requirementImageInput['minHeight']);
                $requirementImage->setMaxHeight($requirementImageInput['maxHeight']);

            } else {
                $scholarship->addRequirementImage($requirementImage = new RequirementImage(
                    $requirementImageInput['requirementName'],
                    $requirementImageInput['title'],
                    $requirementImageInput['description'],
                    $requirementImageInput['fileExtension'] ?: null,
                    $requirementImageInput['maxFileSize'] ?: null,
                    $requirementImageInput['minWidth'] ?: null,
                    $requirementImageInput['maxWidth'] ?: null,
                    $requirementImageInput['minHeight'] ?: null,
                    $requirementImageInput['maxHeight'] ?: null
                ));
            }

            $requirementImageUpdated->add($requirementImage);
        }

        foreach ($scholarship->getRequirementImages() as $requirementImage) {
            if (!$requirementImageUpdated->contains($requirementImage)) {
                $scholarship->removeRequirementImage($requirementImage);
            }
        }

        foreach ((array) $request->get('requirement_input') as $requirementInputInput) {
            if (!$requirementInputInput['permanentTag']) {
                $requirementInputInput['permanentTag'] = substr(uniqid(), 0, 8);
            } else {
                $requirementInputInput['permanentTag'] =
                    preg_replace("/[^a-zA-Z0-9_-]/", '_', $requirementInputInput['permanentTag']);
            }
            $permanentTags[] = $requirementInputInput['permanentTag'];

            if ($requirementInputInput['id']) {
                /** @var RequirementInput $requirementInput */
                $requirementInput = \EntityManager::findById(RequirementInput::class, $requirementInputInput['id']);
                $requirementInput->setRequirementName($requirementInputInput['requirementName']);
                $requirementInput->setTitle($requirementInputInput['title']);
                $requirementInput->setPermanentTag($requirementInputInput['permanentTag']);
                $requirementInput->setDescription($requirementInputInput['description']);
            } else {
                $scholarship->addRequirementInput($requirementInput = new RequirementInput(
                    $requirementInputInput['requirementName'],
                    $requirementInputInput['title'],
                    $requirementInputInput['permanentTag'],
                    $requirementInputInput['description']
                ));
            }

            $requirementInputUpdated->add($requirementInput);
        }

        foreach ($scholarship->getRequirementInputs() as $requirementInput) {
            if (!$requirementInputUpdated->contains($requirementInput)) {
                $scholarship->removeRequirementInput($requirementInput);
            }
        }

        $this->setIsOptionalRequirement($request,
            [
                $requirementTextUpdated,
                $requirementFileUpdated,
                $requirementImageUpdated,
                $requirementInputUpdated,
            ]
        );

        foreach ($scholarship->getRequirementSurvey() as $requirementSurvey) {
            if (!$requirementSurveyUpdated->contains($requirementSurvey)) {
                $scholarship->removeRequirementSurvey($requirementSurvey);
            }
        }

        foreach ((array) $request->get('requirement_survey') as $requirementSurveyInput) {

            if (!$requirementSurveyInput['permanentTag']) {
                $requirementSurveyInput['permanentTag'] = substr(uniqid(), 0, 8);
            } else {
                $requirementSurveyInput['permanentTag'] =
                    preg_replace("/[^a-zA-Z0-9_-]/", '_', $requirementSurveyInput['permanentTag']);
            }
            $permanentTags[] = $requirementSurveyInput['permanentTag'];

            if ($requirementSurveyInput['id']) {
                /** @var RequirementSurvey $requirementSurvey */
                $requirementSurvey = \EntityManager::findById(RequirementSurvey::class, $requirementSurveyInput['id']);
                unset($requirementSurveyInput['id']);
                $requirementSurvey->hydrate($requirementSurveyInput);
                $requirementSurveyUpdated->add($requirementSurvey);
                $scholarship->addRequirementSurvey($requirementSurvey);
            } else {
                if(!is_null($requirementSurveyInput)) {
                    $scholarship->addRequirementSurvey($requirementSurvey = new RequirementSurvey($requirementSurveyInput));
                    $requirementSurveyUpdated->add($requirementSurvey);
                }
            }
        }


        $this->setIsOptionalRequirement($request,
            [
                $requirementTextUpdated,
                $requirementFileUpdated,
                $requirementImageUpdated,
                $requirementInputUpdated,
                $requirementSurveyUpdated,
                $requirementSpecialEligibilityUpdated,
            ]
        );

        $route = route('admin::scholarships.save', ['id' => $scholarship->getScholarshipId()]);

        if (count($permanentTags) != count(array_unique($permanentTags))) {
            return \Redirect::to($route)->withErrors([
                'All tags must be unique in terms of a scholarship'
            ]);
        }

        try {
            \EntityManager::flush();
        } catch(ForeignKeyConstraintViolationException $exception) {
            return \Redirect::to($route)->withErrors([
                'Requirements can not be deleted, they are already used in application.Please <a href="/admin/scholarships/copy?id='.$scholarship->getScholarshipId().'">COPY</a> the scholarship and remove requirements in the copy.'
            ]);
        }

        return \Redirect::to($route);
    }

    /**
     * Post Save Scholarship Information Action
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSaveInformationAction(Request $request)
    {
        $this->validate($request, [
            'title'                     => 'required',
            'url'                       => 'required|url',
            'logo'                      => 'image',
            'image'                     => 'image|dimensions:max_width=688,max_height=280',

            'is_recurrent'              => 'required',
            'recurring_type'            => 'string',
            'recurring_value'           => 'numeric',
            'recurrence_start_now'      => 'boolean',
            'recurrence_end_month'      => 'boolean',
            'timezone'                  => 'required|string',
            'start_date'                => 'required|date',
            'start_date_hour'           => 'required|numeric',
            'start_date_minutes'        => 'required|numeric',
            'expiration_date'           => 'required|date',
            'expiration_date_hour'      => 'required|numeric',
            'expiration_date_minutes'   => 'required|numeric',
            'awards'                    => 'required|numeric',
            'amount'                    => 'required|numeric',
            'up_to'                     => 'numeric',

            'terms_of_service_url'      => 'url',
            'privacy_policy_url'        => 'url',
        ], [
            'image.dimensions' => 'Max image width 688px, height 280px'
        ]);

        $default = [
            'recurrence_start_now' => false,
            'recurrence_end_month' => false,
        ];

        $startDate = new \DateTime($request->get('start_date'));
        $startDate->setTime($request->get('start_date_hour'), $request->get('start_date_minutes'));
        unset($request['start_date']);

        $expirationDate = new \DateTime($request->get('expiration_date'));
        $expirationDate->setTime($request->get('expiration_date_hour'), $request->get('expiration_date_minutes'));
        unset($request['expiration_date']);

        $isStatusUpdated = false;
        /** @var ScholarshipEntity $scholarship */
        if ($scholarship = $this->repository->find($request->get('scholarship_id'))) {
            $isStatusUpdated = (int)$scholarship->getStatus()->getId() !== (int)$request['status'] ||
                (int)$scholarship->isActive() !== (int)$request['is_active'] ||
                $expirationDate != $scholarship->getExpirationDate() ||
                $startDate != $scholarship->getStartDate();
            $scholarship->hydrate($request->all() + $default);
        } else {
            $isStatusUpdated = true;
            $this->em->persist($scholarship = new ScholarshipEntity($request->all()));
        }

        $scholarship->setStartDate($startDate);
        $scholarship->setExpirationDate($expirationDate);

        if ($logo = $request->file('logo')) {
            $scholarship->setLogoFile($logo);
        }

        if ($image = $request->file('image')) {
            $path = '/scholarship/image/' . $request->file('image')->getClientOriginalName();
            $scholarship->setImage($path);
            \Storage::disk('gcs')->put($path, file_get_contents($request->file('image')), Filesystem::VISIBILITY_PUBLIC);
        } else {
			$imageNumber = mt_rand(0, 100);
			$imagePath = "/scholarship/image/{$imageNumber}.jpg";
			$scholarship->setImage($imagePath);
		}

        $this->em->flush($scholarship);

        \Event::dispatch(new ScholarshipUpdatedEvent($scholarship, false, $isStatusUpdated));

        return \Redirect::route('admin::scholarships.save', ['id' => $scholarship->getScholarshipId()]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteLogoAction(Request $request)
    {
        $this->validate($request, ['scholarshipId' => 'required|numeric']);

        /** @var \App\Entity\Scholarship $scholarship */
        if ($scholarship = \EntityManager::find(ScholarshipEntity::class, $request->get('scholarshipId'))) {
            $scholarship->removeLogo();
        } else {
            return \Redirect::route('admin::scholarships.search')->withErrors(
                sprintf('Scholarship with id [ %s ] not found', $request->get('scholarshipId'))
            );
        }

        return \Redirect::route('admin::scholarships.save', ['id' => $scholarship->getScholarshipId()]);
    }

    /**
	 * Post Save Scholarship Application Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveApplicationAction() {
		$model = new JsonModel();

		try {
			$scholarshipService = new ScholarshipService();
			$scholarship = new Scholarship();
			$scholarshipId = 0;

			$input = $this->getAllInput();
			$errors = array();


			$scholarshipId = $input["scholarship_id"];

			if(empty($input["application_type"])) {
				$errors["application_type"] = "Application type is empty !";
			}
			else {

				if($input["application_type"] != Scholarship::APPLICATION_TYPE_NONE) {
					if(empty($input["apply_url"])) {
						$errors["apply_url"] = "Apply url is empty !";
					}
					else if(!filter_var($input["apply_url"], FILTER_VALIDATE_URL)) {
						$errors["apply_url"] = "Apply url not valid !";
					}
				} else {
					$scholarship->populate($input);
					$scholarshipService->saveScholarshipNoneApplication($scholarship);
				}

				if($input["application_type"] == Scholarship::APPLICATION_TYPE_ONLINE) {
					if(empty($input["form_method"])) {
						$errors["form_method"] = "Form method is empty !";
					}

					if(empty($input["form_action"])) {
						$errors["form_action"] = "Form action is empty !";
					}
					else if(!filter_var($input["form_action"], FILTER_VALIDATE_URL)) {
						$errors["form_action"] = "Form action not valid !";
					}
				}
				else if($input["application_type"] == Scholarship::APPLICATION_TYPE_EMAIL) {
					if(empty($input["email"])) {
						$errors["email"] = "Email is empty !";
					}
					else if(filter_var($input["email"], FILTER_VALIDATE_EMAIL) === false) {
						$errors["email"] = "Email not valid !";
					}

					if(empty($input["email_subject"])) {
						$errors["email_subject"] = "Subject is empty !";
					}
				}
			}

			if (empty($scholarshipId)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please save scholarship information first !");
			}
			else if (empty($errors)) {
				if ($input["application_type"] == Scholarship::APPLICATION_TYPE_ONLINE) {
					$scholarship->populate($input);
					$scholarshipService->saveScholarshipOnlineApplication($scholarship);
				}
				else if ($input["application_type"] == Scholarship::APPLICATION_TYPE_EMAIL) {
					$scholarship->populate($input);
					$scholarshipService->saveScholarshipEmailApplication($scholarship);
				}

                \Event::dispatch(new ScholarshipUpdatedEvent($scholarship->getScholarshipId()));

                $model->setStatus(JsonModel::STATUS_OK);
				$model->setMessage("Scholarship application saved !");
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors !");
				$model->setData($errors);
			}
		}
		catch (\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}

	/**
	 * Post Save Scholarship Eligibility Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveEligibilityAction() {
		$model = new JsonModel();

		try {
			$scholarshipService = new ScholarshipService();
			$scholarshipId = 0;
			$eligibilities = array();

			$input = $this->getAllInput();
			$errors = array();


			$scholarshipId = $input["scholarship_id"];

			if (empty($scholarshipId)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please save scholarship information first!");
				return $model->send();
			}

            /** @var ScholarshipRepository $scholarship */
            $scholarshipRepo = \EntityManager::getRepository(\App\Entity\Scholarship::class);
            /** @var \App\Entity\Scholarship $scholarship */
            $scholarship = $scholarshipRepo->findById($scholarshipId);

			if (!empty($input["eligibility_field"])) {
			    if (empty($input["eligibility_type"]) || count($input["eligibility_field"])
			         !== count($input["eligibility_type"])) {
                    $model->setStatus(JsonModel::STATUS_ERROR);
                    $model->setMessage("All fields must be set up!");

                    return $model->send();
			    }

				$eligibilityFields = $input["eligibility_field"];
				$eligibilityTypes = $input["eligibility_type"];
				$eligibilityValues = $input["eligibility_value"];
				$eligibilityIsOptional = $input["eligibility_is_optional"] ?? [];

				$count = count($eligibilityFields);

				for ($i = 0; $i < $count; $i++) {
					if (!empty($eligibilityFields[$i])) {
						$eligibility = new \App\Entity\Eligibility(
						    $eligibilityFields[$i],
						    $eligibilityTypes[$i],
						    $eligibilityValues[$i],
						    $eligibilityIsOptional[$i] ?? 0
						);

						$eligibility->setScholarship($scholarship);
						\EntityManager::persist($eligibility);
					}
				}
			}

            /** @var EligibilityService $eligibilityService */
            $eligibilityService = app()->get(EligibilityService::class);
            // delete all previous eligibilities
            $eligibilityService->deleteEligibilities($scholarship);

            $scholarship->setLastUpdatedDate(new \DateTime());
            // save new ones
            \EntityManager::flush();

            if (empty($errors)) {
				$model->setStatus(JsonModel::STATUS_OK);
				$model->setMessage("Scholarship eligibilities saved!");
                \Event::dispatch(new ScholarshipUpdatedEvent($scholarshipId, true));
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors!");
				$model->setData($errors);
			}
		}
		catch (\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


	/**
	 * Delete Scholarship Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deleteAction() {
		try {
            /** @var \App\Services\ScholarshipService $service */
            $service = app(\App\Services\ScholarshipService::class);
			$id = $this->getQueryParam("id");

			$service->deleteScholarship($id);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/scholarships/search");
	}

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recurAction($id)
    {
        /** @var ScholarshipEntity $scholarship */
        $scholarship = $this->service->recur($this->repository->findById($id));

        return \Redirect::route('admin::scholarships.view', ['id' => $scholarship->getScholarshipId()])->with([
            'message' => 'Scholarship recurred! You looking on new scholarship instance!',
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
	public function copyAction(Request $request)
    {
        $this->validate($request, ['id' => 'required|Entity:Scholarship']);

        /** @var ScholarshipEntity $scholarship */
        $scholarship = $this->em->find(ScholarshipEntity::class, $request->get('id'));

        $this->service->copy($scholarship);

		return $this->redirect("/admin/scholarships/search");
	}

	/**
	 * Fetches Online Form From Url
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function fetchFormAction() {
		$model = new JsonModel();

		$data = array(
			"forms" => "",
			"fields" => array(),
		);

        $getHtml = function($url) {
            $context = stream_context_create(
                [
                    'http' => [
                        'method'=>"GET",
                        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36\r\n" .
                                    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3\r\n" .
                                    "accept-language: es-ES,es;q=0.9,en;q=0.8,it;q=0.7\r\n".
                                    "accept-encoding: gzip, deflate, br\r\n"
                    ]
                ]
            );

			$content = file_get_contents($url, false, $context);
			try {
                $content = gzdecode($content);
            } catch (\Exception $e) {
                // do nothing
            }

            return $content;
        };

		try {
			$url = $this->getQueryParam("url");
			$scholarshipId = $this->getQueryParam("scholarship_id", "");

//$url = 'https://www.physicaltherapyfairbanks.com/fairbanks.php';
            $content = $getHtml($url);
			$dom = DomFactory::getFromString($content);
			$forms = $dom->find("form");

			// IFrame Check
			if (empty($forms)) {
				$iframes = $dom->find("iframe");

				if (!empty($iframes)) {
					$iframe = $iframes[0];
					$url = $iframe->src;

					$content = $getHtml($url);
					$dom = DomFactory::getFromString($content);

					$forms = $dom->find("form");
				}
			}

			$formContent = (string) \View::make("admin/scholarships/online_data", array("forms" => $forms));
			$data["forms"] = preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "<JS>/* $1 */</JS>", $formContent);

			if (!empty($scholarshipId)) {
				$service = new ScholarshipService();

				$onlineData = array();
				$forms = $service->getForm($scholarshipId);
				foreach ($forms as $form) {
					$onlineData[$form->getFormField()] = $form->toArray();
				}

				$data["fields"] = $onlineData;
			}

			$model->setStatus(JsonModel::STATUS_OK);
			$model->setData($data);
		}
		catch (\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


	/**
	 * Fetches Field For Editing
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function fetchFieldAction(Request $request)
    {
        /** @var ScholarshipEntity $scholarship */
        $scholarship = $this->repository->findById($request->get('scholarship_id'));

		$data = array(
			"fields" => \App\Entity\Form::getSystemFields(),
			"field" => null,
			"static_fields" => [
                "gender"       => Profile::getGenders(),
                "citizenship"  => InfoServiceFactory::get("Citizenship")->getAll(false),
                "ethnicity"    => InfoServiceFactory::get("Ethnicity")->getAll(false),
                "degree"       => InfoServiceFactory::get("Degree")->getAll(false),
                "degree_type"  => InfoServiceFactory::get("DegreeType")->getAll(false),
                "gpa_range"    => Profile::getGpaArray(),
                "career_goal"  => InfoServiceFactory::get("CareerGoal")->getAll(false),
                "study_online" => Profile::getStudyOnlineOptions(),
                "school_level" => InfoServiceFactory::get("SchoolLevel")->getAll(false),
            ],
            'requirements' => $this->getRequirements($scholarship),
            'requirementsText' => [],
		);

//        if (!$request->has('form_field')) {
//            return view('admin.scholarships.form.modal_input_custom_field', $data);
//        }

        foreach ($scholarship->getRequirementTexts() as $requirementText) {
            if (!$requirementText->getAllowFile()) {
                $data['requirementsText'][$requirementText->getId()] = sprintf('%s (%s)',
                    $requirementText->getRequirementName(),
                    $requirementText->getTitle()
                );
            }
        }

        if ($formField = $request->get('form_field')) {
            $data['field'] = $this->repository->findFormField($scholarship, $formField);
        }

        return view('admin.scholarships.online_data_modal_field', $data);
	}

    /**
     * @param ScholarshipEntity $scholarship
     *
     * @return array
     */
    protected function getRequirements(ScholarshipEntity $scholarship)
    {
         $requirements = ['texts' => [], 'files' => [], 'images' => [], 'inputs' => []];

        foreach($scholarship->getRequirementTexts() as $requirementText) {
            $requirements['texts'][$requirementText->getId()] = sprintf('%s (%s)',
                $requirementText->getRequirementName(),
                $requirementText->getTitle()
            );
        }

        foreach ($scholarship->getRequirementFiles() as $requirementFile) {
            $requirements['files'][$requirementFile->getId()] = sprintf('%s (%s)',
                $requirementFile->getRequirementName(),
                $requirementFile->getTitle()
            );
        }

        foreach ($scholarship->getRequirementImages() as $requirementImage) {
            $requirements['images'][$requirementImage->getId()] = sprintf('%s (%s)',
                $requirementImage->getRequirementName(),
                $requirementImage->getTitle()
            );
        }

        foreach ($scholarship->getRequirementInputs() as $requirementInput) {
            $requirements['inputs'][$requirementInput->getId()] = sprintf('%s (%s)',
                $requirementInput->getRequirementName(),
                $requirementInput->getTitle()
            );
        }

        return $requirements;
    }

	/**
	 * Post Save Online Data Field
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveFieldAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();

			$scholarshipId = @$input["scholarship_id"];
			$formField = @$input["form_field"];
			$systemField = @$input["system_field"];
			$value = @$input["value"];
			$mapping = @$input["mapping"];

			if (empty($scholarshipId)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please save scholarship information first !");
			}
			else if (empty($formField) || empty($systemField)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please select fields !");
			}
			else {
				$mappingPrepared = array();
				if (!empty($mapping)) {
					foreach ($mapping as $mapping) {
						$arr = explode("###", $mapping);
						$systemValue = $arr[0];
						$formValue = $arr[1];

						if (!array_key_exists($systemValue, $mappingPrepared)) {
							$mappingPrepared[$systemValue] = array();
						}

						@$mappingPrepared[$systemValue][] = $formValue;
					}
				}

				$form = new Form();
				$form->setScholarshipId($scholarshipId);
				$form->setFormField($formField);
				$form->setSystemField($systemField);
				$form->setValue($value);
				$form->setMapping(json_encode($mappingPrepared));

				$service = new ScholarshipService();
				$service->setFormField($form);

				$cacheKey = sprintf("SCHOLARSHIP_FIELD_%d_%s", $scholarshipId, $formField);
				\Cache::put($cacheKey, $form->toArray(), 60 * 60);

				$model->setStatus(JsonModel::STATUS_OK);
				$model->setMessage("Field saved !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


	/**
	 * Post Delete Online Data Field
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postDeleteFieldAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();

			$scholarshipId = @$input["scholarship_id"];
			$formField = @$input["form_field"];

			if (empty($scholarshipId)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please save scholarship information first !");
			}
			else if (empty($formField)) {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please select field !");
			}
			else {
				$service = new ScholarshipService();
				$service->deleteFormField($scholarshipId, $formField);

				$cacheKey = sprintf("SCHOLARSHIP_FIELD_%d_%s", $scholarshipId, $formField);
				\Cache::forget($cacheKey);

				$model->setStatus(JsonModel::STATUS_OK);
				$model->setMessage("Field deleted !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


    /**
     * @param Request $request
     *
     * @return string
     */
	public function testAction(Request $request)
    {
        /** @var Admin $admin */
        $admin = \Auth::user();
        $account = $admin->getAccount();
        $this->validate($request, ['id' => 'required|entity:Scholarship']);

        /** @var ScholarshipEntity $scholarship */
        $scholarship = \EntityManager::findById(ScholarshipEntity::class, $request->get('id'));

        $errorRoute = \Redirect::route('admin::scholarships.save', ['id' => $scholarship->getScholarshipId()]);
        $this->addBreadcrumb("View Scholarship", 'scholarships.view', ['id' => $scholarship->getScholarshipId()]);

        if (\Request::isMethod("get")) {
            switch ($scholarship->getApplicationType()) {
                case ScholarshipEntity::APPLICATION_TYPE_ONLINE;
                    $requirements = $this->mapRequirements($scholarship);
                    $mapping = [];

                    foreach ($scholarship->getForms() as $form) {
                        switch ($form->getSystemField()) {
                            case \App\Entity\Form::TEXT:
                            case \App\Entity\Form::REQUIREMENT_UPLOAD_TEXT:
                                if (!isset($requirements['texts'][$form->getValue()])) {
                                    return $errorRoute->withErrors(sprintf(
                                        'Missing configured text requirement: %s',
                                        $form->getValue()
                                    ));
                                }
                                break;
                            case \App\Entity\Form::REQUIREMENT_UPLOAD_FILE:
                                if (!isset($requirements['files'][$form->getValue()])) {
                                    return $errorRoute->withErrors(sprintf(
                                        'Missing configured file requirement: %s',
                                        $form->getValue()
                                    ));
                                }
                                break;
                            case \App\Entity\Form::REQUIREMENT_UPLOAD_IMAGE:
                                if (!isset($requirements['images'][$form->getValue()])) {
                                    return $errorRoute->withErrors(sprintf(
                                        'Missing configured image requirement: %s',
                                        $form->getValue()
                                    ));
                                }
                                break;
                            case \App\Entity\Form::INPUT:
                                if (!isset($requirements['inputs'][$form->getValue()])) {
                                    return $errorRoute->withErrors(sprintf(
                                        'Missing configured input requirement: %s',
                                        $form->getValue()
                                    ));
                                }
                                break;
                            default:
                                $mapping[$form->getFormId()] = \App\Entity\Form::mapField($form, $account);
                                break;
                        }
                    }

                    return $this->view('Scholarship Online - Test', 'admin.scholarships.test.online', [
                        'scholarship' => $scholarship,
                        'requirements' => $requirements,
                        'mapping' => $mapping,
                    ]);

                case ScholarshipEntity::APPLICATION_TYPE_SUNRISE:
                    $requirementImage = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementImage::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();
                    $requirementFile = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementFile::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();
                    $requirementText = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementText::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();
                    $requirementInput = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementInput::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();
                    $requirementSurvey = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementSurvey::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();

                    $requirementSpElb = \EntityManager::createQueryBuilder()
                        ->select('r')
                        ->from(RequirementSpecialEligibility::class, 'r', 'r.externalId')
                        ->where('r.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();

                    $repo = \EntityManager::getRepository(\App\Entity\Eligibility::class);
                    $eligibilities = $repo->createQueryBuilder('e')
                        ->where('e.scholarship = :scholarship')
                        ->setParameter('scholarship', $scholarship)
                        ->getQuery()
                        ->getResult();

                     $fieldIds = array_map(function(\App\Entity\Eligibility $v) {
                        return $v->getField()->getId();
                     }, $eligibilities);

                     $fieldsMap = $this->reverseEligibilityFieldsMap();
                     $fields = [];
                     foreach ($fieldIds as $fId) {
                         if (isset($fieldsMap[$fId])) {
                            $fields[$fieldsMap[$fId]] = $this->resolveEligibilityField($account, $fId);
                        }
                     }

                    return $this->view('Scholarship Sunrise - Test', 'admin.scholarships.test.sunrise', [
                        'scholarship' => $scholarship,
                        'account' => $account,
                        'states' => InfoServiceFactory::getArrayData("State"),
                        'requirementImage' => $requirementImage,
                        'requirementFile' => $requirementFile,
                        'requirementText' => $requirementText,
                        'requirementInput' => $requirementInput,
                        'requirementSurvey' => $requirementSurvey,
                        'requirementSpElb' => $requirementSpElb,
                        'fields' => $fields
                    ]);

                case ScholarshipEntity::APPLICATION_TYPE_EMAIL;
                    $profile = $account->getProfile();
                    $scholarship->setApplicationTexts([]);
                    $scholarship->setApplicationImages([]);
                    $scholarship->setApplicationFiles([]);
                    $scholarship->setApplicationInputs([]);

                    return $this->view('Scholarship Email - Test', 'admin.scholarships.test.email', [
                        'to' => $scholarship->getEmail(),
                        'from' => [$account->getInternalEmail(), $profile->getFirstName().' '.$profile->getLastName()],
                        'subject' => $account->mapTags($scholarship->getEmailSubject()),
                        'body' => $scholarship->getEmailMessage(),
                        'scholarship' => $scholarship,
                        'requirements' => $scholarship->getRequirements(),
                    ]);

                default:
                    throw new \LogicException('Unknown scholarship send type for testing');
            }
        } else {
            try {
                switch ($scholarship->getApplicationType()) {
                    case ScholarshipEntity::APPLICATION_TYPE_ONLINE:
                        $data = $request->get('data', []);
                        $sender = new ApplicationSenderOnline();

                        $scholarship = $this->prepareTestScholarship($scholarship, $account, $request);
                        $scholarship->setFormAction($request->get('form_action'));
                        $data[ApplicationSenderOnline::REQUEST_FILES] =
                            $sender->prepareSubmitData($scholarship, $account)[ApplicationSenderOnline::REQUEST_FILES];

                        return $sender->sendApplication($scholarship, $data, new Application($account, $scholarship));

                    case ScholarshipEntity::APPLICATION_TYPE_SUNRISE:
                        $attributes = $request->get('attributes', []);
                        $sender = new ApplicationSenderSunrise();
                        $scholarship = $this->prepareTestScholarship($scholarship, $account, $request);
                        $data = $sender->prepareSubmitData($scholarship, $account, $attributes);

                        return $sender->sendApplication($scholarship, $data, new Application($account, $scholarship));

                    case ScholarshipEntity::APPLICATION_TYPE_EMAIL:
                        $data = $request->all();
                        $applicationEmailSender = new ApplicationSenderEmail();

                        $scholarship = $this->prepareTestScholarship($scholarship, $account, $request);
                        $scholarship->setEmailMessage($data['body']);
                        $scholarship->setEmailSubject($data['subject']);
                        $submitData = $applicationEmailSender->prepareSubmitData($scholarship, $account);

                        $data['from'] = [$request->get('from_address'), $request->get('from_name')];
                        $data['body'] = $submitData['body'];
                        $data['subject'] = $submitData['subject'];
                        $data['attachments'] = $submitData['attachments'];
                        return $applicationEmailSender->sendApplication($scholarship, $data, new Application($account, $scholarship));
                        break;
                    default:
                        throw new \LogicException('Unknown scholarship send type for testing');
                        break;
                }
            } catch (\Exception $e) {
                $error = [
                    'File' => $e->getFile(),
                    'Line' => $e->getLine(),
                    'Message' => $e->getMessage(),
                    'Code' => $e->getCode(),
                    'Trace' => $e->getTraceAsString(),
                ];

                return "<pre>" . print_r($error, true) . "</pre>";
            }
        }
	}

    /**
     * @param ScholarshipEntity $scholarship
     * @param Account           $account
     * @param Request           $request
     *
     * @return $this
     */
    protected function prepareTestScholarship(ScholarshipEntity $scholarship, Account $account, Request $request)
    {
        $texts = $request->get('requirement_text', []);
        $inputs = $request->get('requirement_input', []);
        $inputsSpecialEligibility = $request->get('requirement_special_eligibility', []);
        $applicationTexts = [];
        $applicationFiles = [];
        $applicationImages = [];
        $applicationInputs = [];
        $applicationSpecialEligibility = [];
        $applicationSurvey = [];

        foreach ($scholarship->getRequirementTexts() as $requirementText) {
            $inputName = 'requirement_text_file_' . $requirementText->getId();
            $file = $request->hasFile($inputName) ? $request->file($inputName) : null;
            $text = $texts[$requirementText->getId()] ?? null;

            if (empty($file) && empty($text)) {
                throw new \InvalidArgumentException(
                    sprintf('File or text missing for requirement: %s', $requirementText->getId())
                );
            }

            $applicationTexts[] = new ApplicationText(
                $requirementText,
                $file ? new AccountFile($file, $account) : null,
                $text,
                $account
            );
        }

        foreach ($scholarship->getRequirementFiles() as $requirementFile) {
            $inputName = 'requirement_file_' . $requirementFile->getId();
            if ($request->hasFile($inputName)) {
                $applicationFiles[] = new ApplicationFile(
                    new AccountFile($request->file($inputName), $account),
                    $requirementFile
                );
            } else {
                throw new \InvalidArgumentException(
                    sprintf('File missing for requirement %s', $requirementFile->getId())
                );
            }
        }

        foreach ($scholarship->getRequirementImages() as $requirementImage) {
            $inputName = 'requirement_images_' . $requirementImage->getId();
            if ($request->hasFile($inputName)) {
                $applicationImages[] = new ApplicationImage(
                    new AccountFile($request->file($inputName), $account),
                    $requirementImage
                );
            } else {
                throw new \InvalidArgumentException(
                    sprintf('Image missing for requirement %s', $requirementImage->getId())
                );
            }
        }

        foreach ($scholarship->getRequirementInputs() as $requirementInput) {
            if (empty($inputs[$requirementInput->getId()])) {
                throw new \InvalidArgumentException(
                    sprintf('Missing input for requirement: %s', $requirementInput->getId())
                );
            }

            $applicationInputs[] = new ApplicationInput(
                $requirementInput,
                $account,
                $inputs[$requirementInput->getId()]
            );
        }

        /**
         * @var RequirementSurvey $requirementSurvey
         */
        foreach ($scholarship->getRequirementSurvey() as $requirementSurvey) {
            $surveyInput = $request->get('requirement_survey', []);

            if (empty($surveyInput[$requirementSurvey->getId()])) {
                throw new \InvalidArgumentException(
                    sprintf('Missing input for requirement: %s', $requirementSurvey->getId())
                );
            }
            $answers = $requirementSurvey->convertAnswerArray([$surveyInput[$requirementSurvey->getId()]]);

            $applicationSurvey[] = new ApplicationSurvey(
                $requirementSurvey,
                $answers,
                $account
            );
        }

        foreach ($scholarship->getRequirementSpecialEligibility() as $requirementSpecialEligibility) {
            $val = isset($inputsSpecialEligibility[$requirementSpecialEligibility->getId()]) ? 1:  0;

            $applicationSpecialEligibility[] = new ApplicationSpecialEligibility(
                $requirementSpecialEligibility,
                $account,
                $val
            );
        }

        return $scholarship
            ->setApplicationTexts($applicationTexts)
            ->setApplicationFiles($applicationFiles)
            ->setApplicationImages($applicationImages)
            ->setApplicationInputs($applicationInputs)
            ->setApplicationSpecialEligibility($applicationSpecialEligibility)
            ->setApplicationSurveys($applicationSurvey);
    }

    /**
     * @param ScholarshipEntity $scholarship
     *
     * @return array
     */
    protected function mapRequirements(ScholarshipEntity $scholarship)
    {
        $requirementTexts = [];
        foreach ($scholarship->getRequirementTexts() as $requirementText) {
            $requirementTexts[$requirementText->getId()] = $requirementText;
        }

        $requirementFiles = [];
        foreach ($scholarship->getRequirementFiles() as $requirementFile) {
            $requirementFiles[$requirementFile->getId()] = $requirementFile;
        }

        $requirementImages = [];
        foreach ($scholarship->getRequirementImages() as $requirementImage) {
            $requirementImages[$requirementImage->getId()] = $requirementImage;
        }

        $requirementInputs = [];
        foreach ($scholarship->getRequirementInputs() as $requirementInput) {
            $requirementInputs[$requirementInput->getId()] = $requirementInput;
        }

        return [
            'texts' => $requirementTexts,
            'files' => $requirementFiles,
            'images' => $requirementImages,
            'inputs' => $requirementInputs,
        ];
    }

	/**
	 * SuperCollege API Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function superCollegeAction() {
		$model = new ViewModel("admin/scholarships/supercollege/search");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
				"SuperCollege API" => "/admin/scholarships/super-college"
			),
			"title" => "SuperCollege API",
			"active" => "scholarships",
			"scholarships" => array(),
			"search" => array(
				"usertype" => "18",
				"sex" => "1",
				"citizen" => "1",
				"age" => "18",
				"major" => "0",
				"state" => "0",
				"disability" => "0",
				"athletics" => "",
				"interest" => "0",
				"career" => "0",
				"religion" => "0",
				"race" => "",
				"membership" => "",
				"military" => "",
				"circumstance" => "",
			),
			"options" => array(
				"usertype" => SuperCollegeTypes::getUserTypes(),
				"sex" => SuperCollegeTypes::getSex(),
				"citizen" => SuperCollegeTypes::getCitizenships(),
				'major' => SuperCollegeTypes::getMajors(),
				"state" => array("0" => " -- Select one --") + SuperCollegeTypes::getStates(),
				"disability" => SuperCollegeTypes::getDisabilities(),
				"athletics" => SuperCollegeTypes::getAthletics(),
				"interest" => SuperCollegeTypes::getInterests(),
				"career" => SuperCollegeTypes::getCareers(),
				"religion" => SuperCollegeTypes::getReligions(),
				"race" => SuperCollegeTypes::getRaces(),
				"membership" => array("" => "-- Select one --") + SuperCollegeTypes::getMemberships(),
				"military" => array("" => "-- Select one --") + SuperCollegeTypes::getMilitaries(),
				"circumstance" => array("" => "-- Select one --") + SuperCollegeTypes::getCircumstances(),
			),
		);

		try {
            $service = new SuperCollegeService();
			$inputParams = array();

			foreach($data["search"] as $key => $value) {
				$input = $this->getQueryParam($key);

				if(!empty($input)) {
					if (is_array($input)) {
						$input = implode(',', $input);
					}

					$inputParams[$key] = $input;
				}
			}

			foreach($data['search'] as $key => $value) {
				if (isset($inputParams[$key])) {
					if (in_array($key, array('major', 'career', 'interest', 'race', 'religion', 'disability', 'athletics'))) {
						$data['search'][$key] = explode(',', $inputParams[$key]);
					}
					else {
						$data['search'][$key] = $inputParams[$key];
					}
				}
			}

			$data["scholarships"] = $service->findMatches($inputParams);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * SuperCollege View Scholarship Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function superCollegeViewAction() {
		$model = new ViewModel("admin/scholarships/supercollege/view");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Scholarships" => "/admin/scholarships",
				"SuperCollege API" => "/admin/scholarships/super-college"
			),
			"title" => "SuperCollege Scholarship",
			"active" => "scholarships",
			"scholarship" => array()
		);

		try {
            $service = new SuperCollegeService();
			$uuid = $this->getQueryParam("uuid");

			if(!empty($uuid)) {
				$scholarship = $service->getDetails($uuid);

				$data["scholarship"] = $scholarship;
				$data["title"] = $scholarship->SCHOL_NM;
				$data["breadcrumb"]["View Scholarship"] = "/admin/scholarships/super-college/view?uuid=$uuid";
			}
			else {
				throw new \Exception("SuperCollege scholarship UUID not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}

    /**
     * SuperCollege Eligibility Action
     *
     * @access public
     * @return Response
     *
     */
    public function superCollegeEligibilityAction() {
        $model = new ViewModel("admin/scholarships/supercollege/eligibility");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Scholarships" => "/admin/scholarships",
                "SuperCollege Eligibility" => "/admin/scholarships/super-college-eligibility"
            ),
            "title" => "SuperCollege API",
            "active" => "scholarships",
            "scholarships" => array(),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/scholarships/super-college-eligibility",
                "url_params" => array()
            ),
        );

        try {
            $page = $this->getQueryParam("page", 1);
            $dql = "SELECT 
                    scs, COUNT(1) AS num_matches
                FROM
                    App\Entity\SuperCollegeScholarship scs
                    LEFT JOIN scs.superCollegeScholarshipMatches scsm
                GROUP BY
                    scs.id
                ORDER BY
                    num_matches DESC";

            $query = \EntityManager::createQuery($dql)
                ->setFirstResult(($page - 1)* 50)
                ->setMaxResults(50);

            $paginator = new Paginator($query);
            $data["count"] = count($paginator);

            $data["scholarships"] = $paginator;

            $data["pagination"]["page"] = $page;
            $data["pagination"]["pages"] = ceil($data["count"] / 50);
        }
        catch(\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }

    public function superCollegeMatchAction(){
        $model = new ViewModel("admin/scholarships/supercollege/match");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Scholarships" => "/admin/scholarships",
                "SuperCollege Eligibility" => "/admin/scholarships/super-college-eligibility"
            ),
            "title" => "SuperCollege Scholarship",
            "active" => "scholarships",
            "scholarship" => array()
        );

        try {
            $id = $this->getQueryParam("id");

            if(!empty($id)) {
                /** @var SuperCollegeScholarship $scholarship */
                $scholarship = \EntityManager::getRepository(SuperCollegeScholarship::class)->find($id);

                $data["scholarship"] = $scholarship;
                $data["title"] = $scholarship->getTitle();
                $data["breadcrumb"]["View Scholarship"] = "/admin/scholarships/super-college-match?id=$id";
            }
            else {
                throw new \Exception("SuperCollege scholarship id not provided !");
            }
        }
        catch(\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }
}
