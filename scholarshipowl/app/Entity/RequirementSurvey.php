<?php namespace App\Entity;

use App\Entity\Contracts\RequirementContract;
use App\Entity\Traits\Dictionary;
use App\Entity\Traits\Hydratable;
use App\Entity\Traits\RequirementTag;
use App\Traits\OptionalRequirement;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * RequirementSurvey
 *
 * @ORM\Table(name="requirement_survey", indexes={@ORM\Index(name="requirement_survey_scholarship_id_foreign", columns={"scholarship_id"}), @ORM\Index(name="requirement_survey_requirement_name_id_foreign", columns={"requirement_name_id"})})
 * @ORM\Entity
 */
class RequirementSurvey implements RequirementContract
{
    use Hydratable,
        RequirementTag,
        Timestamps,
        OptionalRequirement;

    const TYPE = 'survey';

    const SURVEY_TYPE_RADIO = 'radio';
    const SURVEY_TYPE_CHECKBOX = 'checkbox';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="external_id", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="survey", type="json", nullable=false)
     */
    private $survey;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_tag", type="string", length=20, nullable=true, unique=false)
     */
    private $permanentTag;

    /**
     * @var RequirementName
     *
     * @ORM\ManyToOne(targetEntity="RequirementName")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="requirement_name_id", referencedColumnName="id")
     * })
     */
    private $requirementName;

    /**
     * @var Scholarship
     *
     * @ORM\ManyToOne(targetEntity="Scholarship")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     * })
     */
    private $scholarship;

    /**
     * Sunrise's requirement id (permanent, does not change between recurrences)
     *
     * @var integer
     *
     * @ORM\Column(name="external_id_permanent", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $externalIdPermanent;

    /**
     * RequirementSurvey constructor.
     *
     * @param array $hydrateData
     */
    public function __construct(array $hydrateData)
    {
        $this->hydrate($hydrateData);
    }

    public function getRequirementName()
    {
        return $this->requirementName;
    }

    public function getApplicationClass()
    {
        return ApplicationSurvey::class;
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return int
     */
    public function getExternalIdPermanent()
    {
        return $this->externalIdPermanent;
    }

    /**
     * @param int $externalId
     */
    public function setExternalId(int $externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param  $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param array $survey
     */
    public function setSurvey(array $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @param \RequirementName $requirementName
     */
    public function setRequirementName($requirementName)
    {
        $requirementName = RequirementName::convert($requirementName);

        if ($requirementName->getType() !== RequirementName::TYPE_SURVEY) {
            throw new \InvalidArgumentException(sprintf(
                'requirementName should be type of %s (%s given)', RequirementName::TYPE_SURVEY, $requirementName->getType()
            ));
        }

        $this->requirementName = $requirementName;

        return $this;
    }

    /**
     * @param \Scholarship $scholarship
     */
    public function setScholarship($scholarship)
    {
        $this->scholarship = $scholarship;
        return $this;
    }

    /**
     * @param int $externalIdPermanent
     */
    public function setExternalIdPermanent(int $externalIdPermanent)
    {
        $this->externalIdPermanent = $externalIdPermanent;
    }

    /**
     * @return json
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * @return json
     */
    public function getSurveyWithId()
    {
        $survey = $this->getSurvey();
        foreach ($survey as &$item) {
            $item['id'] = $this->generateIdForSurveyQuestion($item);
            $item['description'] = nl2br($item['description']);
        }

        return $survey;
    }

    /**
     * @return string
     */
    public function getPermanentTag()
    {
        return $this->permanentTag;
    }

    /**
     * @param string $permanentTag
     */
    public function setPermanentTag(string $permanentTag)
    {
        $this->permanentTag = $permanentTag;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship(): Scholarship
    {
        return $this->scholarship;
    }

    /**
     * @param $question
     * @return string
     */
    public function generateIdForSurveyQuestion($question)
    {
        return mb_substr(md5($question['question'].$question['type']), 0, 8);
    }

    /**
     * @param array $answers
     * @return array
     */
    public function convertAnswerArray(array $answers)
    {
        $survey = $this->getSurveyWithId();
        $indexed = [];
        foreach ($survey as $i) {
            $indexed[$i['id']] = $i;
        }

        $answersForStore = [];
        foreach (array_shift($answers) as $id => $answer) {
            $question = $indexed[$id];
            $answersForStore[] = [
                'type' => $question['type'],
                'options' => $answer,
                'question' => $question['question']
            ];
        }

        return $answersForStore;
    }

    /**
     * @return array|false
     */
    public static function getQuestionTypes()
    {
        return [
            self::SURVEY_TYPE_CHECKBOX => 'Multiple choice',
            self::SURVEY_TYPE_RADIO => 'Single answer'
        ];
    }

    public function __clone()
    {
        if (!self::getScholarship()->isRecurrent()) {
            $this->permanentTag = substr(uniqid(), -8);
        }
    }
}
