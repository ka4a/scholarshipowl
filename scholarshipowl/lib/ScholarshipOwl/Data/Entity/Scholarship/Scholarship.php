<?php

/**
 * Scholarship
 *
 * @package     ScholarshipOwl\Data\Entity\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	29. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Scholarship;

use App\Entity\ScholarshipStatus;
use ScholarshipOwl\Data\Entity\AbstractEntity;


class Scholarship extends AbstractEntity {
	const APPLICATION_TYPE_ONLINE = "online";
	const APPLICATION_TYPE_EMAIL = "email";
	const APPLICATION_TYPE_SUNRISE = "sunrise";
	const APPLICATION_TYPE_NONE = "none";

	const FORM_METHOD_POST = "post";
	const FORM_METHOD_GET = "get";

    private $scholarshipId;
	private $title;
	private $url;
	private $startDate;
    private $expirationDate;
	private $amount;
	private $upTo;
	private $awards;
	private $description;
    private $logo;
    private $image;
	private $applicationType;
	private $applyUrl;
	private $email;
	private $emailSubject;
	private $emailMessage;
	private $formAction;
	private $formMethod;
	private $termsOfServiceUrl;
	private $privacyPolicyUrl;
    private $status;
    private $is_active = 0;
	private $free;
	private $createdDate;
	private $lastUpdatedDate;
	private $automatic;
    protected $externalScholarshipId;
    protected $externalScholarshipTemplateId;


	private $forms;
	private $eligibilities;

	private $filesAlowed;
    private $sendToPrivate;

    private $isRecurrent;

	public function __construct($scholarshipId = null) {
		$this->scholarshipId = $scholarshipId;
		$this->title = "";
		$this->url = "";
		$this->expirationDate = "";
		$this->amount = null;
		$this->upTo = null;
		$this->awards = null;
		$this->description = "";
        $this->logo = null;
        $this->image = null;
		$this->applicationType = "";
		$this->applyUrl = "";
		$this->email = "";
		$this->emailSubject = "";
		$this->emailMessage = "";
		$this->formAction = "";
		$this->formMethod = "";
		$this->termsOfServiceUrl = "";
		$this->privacyPolicyUrl = "";
		$this->free = 0;
		$this->createdDate = "";
		$this->lastUpdatedDate = "";
		$this->automatic = 0;
        $this->sendToPrivate = 0;
        $this->status = ScholarshipStatus::UNPUBLISHED;
        $this->is_active = 0;
        $this->is_recurrent = 0;

		$this->forms = array();
		$this->eligibilities = array();
		$this->lastAlowed = false;
	}

	public function getScholarshipId(){
		return $this->scholarshipId;
	}

	public function setScholarshipId($scholarshipId){
		$this->scholarshipId = $scholarshipId;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getExpirationDate(){
		return $this->expirationDate;
	}

	public function setExpirationDate($expirationDate){
		$this->expirationDate = $expirationDate;
	}

	public function getAmount(){
		return $this->amount;
	}

	public function setAmount($amount){
		$this->amount = $amount;
	}

	public function getUpTo(){
		return $this->upTo;
	}

	public function setUpTo($upTo){
		$this->upTo = $upTo;
	}

	public function getAwards(){
		return $this->awards;
	}

	public function setAwards($awards){
		$this->awards = $awards;
	}

	public function getDescription(){
		return $this->description;
	}

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function setDescription($description){
		$this->description = $description;
	}

	public function getApplicationType(){
		return $this->applicationType;
	}

	public function setApplicationType($applicationType){
		$this->applicationType = $applicationType;
	}

	public function getApplyUrl(){
		return $this->applyUrl;
	}

	public function setApplyUrl($applyUrl){
		$this->applyUrl = $applyUrl;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getEmailSubject(){
		return $this->emailSubject;
	}

	public function setEmailSubject($emailSubject){
		$this->emailSubject = $emailSubject;
	}

	public function getEmailMessage(){
		return $this->emailMessage;
	}

	public function setEmailMessage($emailMessage){
		$this->emailMessage = $emailMessage;
	}

	public function getFormAction(){
		return $this->formAction;
	}

	public function setFormAction($formAction){
		$this->formAction = $formAction;
	}

	public function getFormMethod(){
		return $this->formMethod;
	}

	public function setFormMethod($formMethod){
		$this->formMethod = $formMethod;
	}

	public function getTermsOfServiceUrl(){
		return $this->termsOfServiceUrl;
	}

	public function setTermsOfServiceUrl($termsOfServiceUrl){
		$this->termsOfServiceUrl = $termsOfServiceUrl;
	}

	public function getPrivacyPolicyUrl(){
		return $this->privacyPolicyUrl;
	}

	public function setPrivacyPolicyUrl($privacyPolicyUrl){
		$this->privacyPolicyUrl = $privacyPolicyUrl;
	}

    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function isFree() {
		return $this->free;
	}

	public function setFree($free) {
		$this->free = $free;
	}

	public function getCreatedDate(){
		return $this->createdDate;
	}

	public function setCreatedDate($createdDate){
		$this->createdDate = $createdDate;
	}

    public function setIsRecurrent($isRecurrent)
    {
        $this->isRecurrent = $isRecurrent;
    }

    public function getIsRecurrent()
    {
        return $this->isRecurrent;
    }

    public function getLastUpdatedDate(){
		return $this->lastUpdatedDate;
	}

	public function setLastUpdatedDate($lastUpdatedDate){
		$this->lastUpdatedDate = $lastUpdatedDate;
	}

	public function isAutomatic() {
		return $this->automatic;
	}

	public function setAutomatic($automatic) {
		$this->automatic = $automatic;
	}

    public function setSendToPrivate($sendToPrivate)
    {
        $this->sendToPrivate = $sendToPrivate;
    }

    public function getSendToPrivate()
    {
        return $this->sendToPrivate;
    }

	public function addForm(Form $form) {
		$this->forms[] = $form;
	}

    /**
     * @return Form[]
     */
	public function getForms() {
		return $this->forms;
	}

	public function setForms($forms) {
		foreach($forms as $form) {
			$this->addForm($form);
		}
	}

	public function addEligibility(Eligibility $eligibility) {
		$this->eligibilities[] = $eligibility;
	}

	public function getScholarshipPageUrl() {
		return $this->getScholarshipId() . "-" . \Str::slug($this->getTitle());
	}

    /**
     * @return Eligibility[]
     */
	public function getEligibilities() {
		return $this->eligibilities;
	}

	public function setEligibilities($eligibilities) {
		foreach($eligibilities as $eligibility) {
			$this->addEligibility($eligibility);
		}
	}

	public function getFilesAlowed(){
		return $this->filesAlowed;
	}

	public function setFilesAlowed($filesAlowed){
		$this->filesAlowed = $filesAlowed;
	}

	public function isExpired() {
        return $this->getStatus() == ScholarshipStatus::EXPIRED;
	}

	public function __toString() {
		return $this->title;
	}

    /**
     * @param int $externalScholarshipId
     *
     * @return Scholarship
     */
    public function setExternalScholarshipId($externalScholarshipId)
    {
        $this->externalScholarshipId = $externalScholarshipId;

        return $this;
    }

    /**
     * @param int $externalScholarshipTemplateId
     *
     * @return Scholarship
     */
    public function setExternalScholarshipTemplateId($externalScholarshipTemplateId)
    {
        $this->externalScholarshipTemplateId = $externalScholarshipTemplateId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExternalScholarshipTemplateId()
    {
        return $this->externalScholarshipTemplateId;
    }

    /**
     * @return int|null
     */
    public function getExternalScholarshipId()
    {
        return $this->externalScholarshipId;
    }

	public static function getApplicationTypes() {
		return array(
			self::APPLICATION_TYPE_ONLINE => "Online",
			self::APPLICATION_TYPE_EMAIL => "Email",
			self::APPLICATION_TYPE_SUNRISE => "Sunrise",
			self::APPLICATION_TYPE_NONE => "Only Database",
		);
	}

	public static function getFormMethods() {
		return array(
			self::FORM_METHOD_GET => "GET",
			self::FORM_METHOD_POST => "POST"
		);
	}

    public function getPublicUrl()
    {
        return \URL::route('scholarships.view', [
            'id' => $this->getScholarshipId(),
            'slug' => \Str::slug($this->getTitle()),
        ]);
    }

    public function isActive()
    {
        return $this->getIsActive();
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }

    public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "scholarship_id") {
				$this->setScholarshipId($value);
			}
			else if($key == "title") {
				$this->setTitle($value);
			}
			else if($key == "url") {
				$this->setUrl($value);
			}
            else if($key == "start_date") {
                $this->setStartDate($value);
            }
            else if($key == "is_recurrent") {
                $this->setIsRecurrent($value);
            }
			else if($key == "expiration_date") {
				$this->setExpirationDate($value);
			}
			else if($key == "amount") {
				$this->setAmount($value);
			}
			else if($key == "up_to") {
				$this->setUpTo($value);
			}
			else if($key == "awards") {
				$this->setAwards($value);
			}
			else if($key == "description") {
				$this->setDescription($value);
			}
            else if($key == "logo") {
                $this->setLogo($value);
            }
            else if($key == "image") {
                $this->setImage($value);
            }
			else if($key == "application_type") {
				$this->setApplicationType($value);
			}
			else if($key == "apply_url") {
				$this->setApplyUrl($value);
			}
			else if($key == "email") {
				$this->setEmail($value);
			}
			else if($key == "email_subject") {
				$this->setEmailSubject($value);
			}
			else if($key == "email_message") {
				$this->setEmailMessage($value);
			}
			else if($key == "form_action") {
				$this->setFormAction($value);
			}
			else if($key == "form_method") {
				$this->setFormMethod($value);
			}
			else if($key == "terms_of_service_url") {
				$this->setTermsOfServiceUrl($value);
			}
			else if($key == "privacy_policy_url") {
				$this->setPrivacyPolicyUrl($value);
			}
            else if($key == "status") {
                $this->setStatus(intval($value));
            }
			else if($key == "is_free") {
				$this->setFree($value);
			}
			else if($key == "created_date") {
				$this->setCreatedDate($value);
			}
			else if($key == "last_updated_date") {
				$this->setLastUpdatedDate($value);
			}
			else if($key == "files_alowed") {
				$this->setFilesAlowed($value);
			}
			else if($key == "is_automatic") {
				$this->setAutomatic($value);
			}
            else if($key == "send_to_private") {
                $this->setSendToPrivate($value);
            }
            else if($key == "is_active") {
                $this->setIsActive($value);
            }
            else if($key == "is_recurrent") {
                $this->setIsRecurrent($value);
            }
            else if($key == "external_scholarship_id") {
                $this->setExternalScholarshipId($value);
            }
            else if($key == "external_scholarship_template_id") {
                $this->setExternalScholarshipTemplateId($value);
            }
		}
	}

	public function toArray() {
		return array(
			"scholarship_id" => $this->getScholarshipId(),
			"title" => $this->getTitle(),
			"url" => $this->getUrl(),
            "start_date" => $this->getStartDate(),
			"expiration_date" => $this->getExpirationDate(),
			"amount" => $this->getAmount(),
			"up_to" => $this->getUpTo(),
			"awards" => $this->getAwards(),
			"description" => $this->getDescription(),
            "logo" => $this->getLogo(),
			"image" => $this->getImage(),
			"application_type" => $this->getApplicationType(),
			"apply_url" => $this->getApplyUrl(),
			"email" => $this->getEmail(),
			"email_message" => $this->getEmailMessage(),
			"email_subject" => $this->getEmailSubject(),
			"form_action" => $this->getFormAction(),
			"form_method" => $this->getFormMethod(),
			"terms_of_service_url" => $this->getTermsOfServiceUrl(),
			"privacy_policy_url" => $this->getPrivacyPolicyUrl(),
            "status" => $this->getStatus(),
            "is_active" => $this->getIsActive(),
			"is_free" => $this->isFree(),
			"is_recurrent" => $this->getIsRecurrent(),
			"created_date" => $this->getCreatedDate(),
			"last_updated_date" => $this->getLastUpdatedDate(),
			"files_alowed" => $this->getFilesAlowed(),
			"is_automatic" => $this->isAutomatic(),
            "send_to_private" => $this->getSendToPrivate(),
		);
	}
}
