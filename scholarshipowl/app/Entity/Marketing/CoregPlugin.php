<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 24/8/2017
 */

namespace App\Entity\Marketing;

use App\Entity\Marketing\Coreg\CoregRequirementsRuleSet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * CoregPlugin
 *
 * @ORM\Table(name="coreg_plugins")
 * @ORM\Entity
 */
class CoregPlugin implements \JsonSerializable
{
    /* Coreg Names */
    const NAME_ACADEMIX = "Academix";
    const NAME_BERECRUITED = "Berecruited";
    const NAME_CAPPEX = "Cappex";
    const NAME_CHRISTIAN_CONNECTOR = "ChristianConnector";
    const NAME_COLLEGE_EXPRESS = "CollegeExpress";
    const NAME_CWL = "Cwl";
    const NAME_DANE_MEDIA = "DaneMedia";
    const NAME_DOUBLE_POSITIVE = "DoublePositive";
    const NAME_LOAN = "Loan";
    const NAME_OPINION_OUTPOST = "Opinionoutpost";
    const NAME_SIMPLE_TUITION = "SimpleTuition";
    const NAME_TOLUNA = "Toluna";
    const NAME_WAY_UP = "WayUp";
    const NAME_ZU_USA = "ZuUsa";
    const NAME_ZIPRECRUITER = "Ziprecruiter";
    const NAME_BIRDDOG_AA = "BirdDogAA";
    const NAME_BIRDDOG_ASIAN = "BirdDogAsian";
    const NAME_BIRDDOG_FEMALE = "BirdDogHispanic";
    const NAME_BIRDDOG_HISPANIC = "BirdDogFemale";
    const NAME_BIRDDOG_NUPOC = "BirdDogNUPOC";
    const NAME_BIRDDOG_GENOFFICER = "BirdDogGenOfficer";
    const NAME_BIRDDOG_MALE = "BirdDogMale";

    const NAME_BIRDDOG_ARMY_RESERVE  = "BirdDogArmyReserve";

    const NAME_INBOXDOLLARS  = "InboxDollars";
    const NAME_ISAY  = "ISay";
    const NAME_GOSSAMERSCIENCE  = "GossamerScience";


    /* Coreg Positions */
    const NAME_NONE = "none";
    const NAME_COREG_1 = "coreg1";
    const NAME_COREG_1_A = "coreg1a";
    const NAME_COREG_2 = "coreg2";
    const NAME_COREG_2_A = "coreg2a";
    const NAME_COREG_3 = "coreg3";
    const NAME_COREG_3_A = "coreg3a";
    const NAME_COREG_4 = "coreg4";
    const NAME_COREG_5 = "coreg5";
    const NAME_COREG_5_A = "coreg5a";
    const NAME_COREG_6 = "coreg6";
    const NAME_COREG_6_A = "coreg6a";

    /**
     * @var integer
     *
     * @ORM\Column(name="coreg_plugin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_visible", type="boolean", nullable=true)
     */
    private $isVisible;

    /**
     * @var boolean
     *
     * @ORM\Column(name="just_collect", type="boolean", nullable=true)
     */
    private $justCollect;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=255, nullable=true)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="display_position", type="string", length=45, nullable=false)
     */
    private $displayPosition;

    private $coregRequirementsRuleSet;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthly_cap", type="integer", nullable=true)
     */
    private $monthlyCap;

    /**
     * @var string
     *
     * @ORM\Column(name="extra", type="text", nullable=true)
     */
    private $extra = null;


    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function getVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param bool $isVisible
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return bool
     */
    public function isJustCollect()
    {
        return $this->justCollect;
    }

    /**
     * @param bool $justCollect
     *
     * @return CoregPlugin
     */
    public function setJustCollect(bool $justCollect)
    {
        $this->justCollect = $justCollect;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getDisplayPosition()
    {
        return $this->displayPosition;
    }

    /**
     * @param string $displayPosition
     */
    public function setDisplayPosition($displayPosition)
    {
        $this->displayPosition = $displayPosition;
        return $this;
    }

    /**
     * @return RedirectRulesSet|null
     */
    public function getRedirectRulesSet()
    {
        return $this->redirectRulesSet;
    }

    /**
     * @return array
     */
    public function getCoregRequirementsRuleSet()
    {
        $sets = $this->coregRequirementsRuleSet;

        if (is_null($sets) && $this->getId() != null) {
            $cacheKey = sprintf('coreg_rule_sets_%d', $this->getId());
            $sets = \Cache::tags([CoregRequirementsRuleSet::CACHE_TAG])->get($cacheKey);

            if ($sets === null) {
                $sets = \EntityManager::getRepository(CoregRequirementsRuleSet::class)->findBy(['coreg' => $this->getId()]);
                \Cache::tags([CoregRequirementsRuleSet::CACHE_TAG])->put($cacheKey, $sets, 60 * 24 * 7);
            }
        }

        if (!$sets) {
            $sets[] = new CoregRequirementsRuleSet();
        }

        //should return array of rule set
        return $sets instanceof CoregRequirementsRuleSet? [$sets] :  $sets;
    }

    /**
     * @param RedirectRulesSet $redirectRulesSet
     */
    public function setRedirectRulesSet(RedirectRulesSet $redirectRulesSet)
    {
        $this->redirectRulesSet = $redirectRulesSet;
        return $this;
    }

    /**
     * @param array $coregRequirementsRuleSet[]
     *
     * @return $this
     */
    public function setCoregRequirementsRuleSet($coregRequirementsRuleSets)
    {
        $this->coregRequirementsRuleSet = $coregRequirementsRuleSets;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMonthlyCap()
    {
        return $this->monthlyCap;
    }

    /**
     * @param int $monthlyCap
     */
    public function setMonthlyCap($monthlyCap)
    {
        $this->monthlyCap = $monthlyCap;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     *
     * @return CoregPlugin
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    public static function getNames()
    {
        return array(
            self::NAME_TOLUNA => "Toluna",
            self::NAME_ACADEMIX => "Academix",
            self::NAME_LOAN => "Loan",
            self::NAME_BERECRUITED => "Berecruited",
            self::NAME_CAPPEX => "Cappex",
            self::NAME_DANE_MEDIA => "Dane Media",
            self::NAME_OPINION_OUTPOST => "Opinion Outpost",
            self::NAME_SIMPLE_TUITION => "Simple Tuition",
            self::NAME_ZU_USA => "Zu USA",
            self::NAME_WAY_UP => "WayUp",
            self::NAME_CWL => "Cwl",
            self::NAME_DOUBLE_POSITIVE => "Double Positive",
            self::NAME_CHRISTIAN_CONNECTOR => "Christian Connector",
            self::NAME_COLLEGE_EXPRESS => "College Express",
            self::NAME_ZIPRECRUITER => "Ziprecruiter",
            self::NAME_BIRDDOG_AA => "Birddog AA",
            self::NAME_BIRDDOG_ASIAN => "BirdDog Asian",
            self::NAME_BIRDDOG_FEMALE => "BirdDog Hispanic",
            self::NAME_BIRDDOG_HISPANIC => "BirdDog Female",
            self::NAME_BIRDDOG_MALE => "BirdDog Male",
            self::NAME_BIRDDOG_NUPOC => "BirdDog NUPOC",
            self::NAME_BIRDDOG_GENOFFICER => "BirdDog Gen Officer",

            self::NAME_BIRDDOG_ARMY_RESERVE => "BirdDog Army Reserve",

            self::NAME_INBOXDOLLARS => "InboxDollars",
            self::NAME_ISAY => "ISay",
            self::NAME_GOSSAMERSCIENCE => "GossamerScience",
        );
    }

    public static function getPositions()
    {
        return array(
            self::NAME_NONE => "none",
            self::NAME_COREG_1 => "coreg1",
            self::NAME_COREG_1_A => "coreg1a",
            self::NAME_COREG_2 => "coreg2",
            self::NAME_COREG_2_A => "coreg2a",
            self::NAME_COREG_3 => "coreg3",
            self::NAME_COREG_3_A => "coreg3a",
            self::NAME_COREG_4 => "coreg4",
            self::NAME_COREG_5 => "coreg5",
            self::NAME_COREG_5_A => "coreg5a",
            self::NAME_COREG_6 => "coreg6",
            self::NAME_COREG_6_A => "coreg6a",
        );
    }

    public static function getBirdDogsCoregList(){

        //from mobile coreg name came  in lower case
        return  [
            'BirdDogAA',
            'BirdDogAsian',
            'BirdDogHispanic',
            'BirdDogFemale',
            'BirdDogNUPOC',
            'BirdDogArmyReserve',
            'BirdDogGenOfficer',
            'BirdDogMale',
            'birddogaa',
            'birddogasian',
            'birddoghispanic',
            'birddogfemale',
            'birddognupoc',
            'birddogarmyreserve',
            'birddoggenofficer',
            'birddogmale'
        ];
    }

    public function getHtml()
    {
        $extraFields = [];

        $name = $this->getName();

        if (!is_null($this->getExtra())) {
            $extraProps = json_decode($this->getExtra(), true);
            $name = $this->getName();

            foreach ($extraProps as $prop){
                $fieldName = sprintf(
                    'coregs[%s][extra][%s]', $name, $prop['name']
                );
                $extraFields[] = \Form::hidden($fieldName, $prop['value']);
            }
        }

        if (\View::exists("includes.coreg." . strtolower($this->getName()))) {

            $idInput = \Form::hidden(sprintf('coregs[%s][id]', $name), $this->getId());
            $extraCheckbox = '';

            if (in_array(strtolower($this->getName()), [
                'birddogaa',
                'birddogasian',
                'birddoghispanic',
                'birddogfemale',
                'birddogmale',
                'birddognupoc',
                'birddoggenofficer',
            ])
            ) {
                $extraCheckbox = \Form::checkbox(sprintf('coregs[%s][extra][sms]', $name), 1, false, [
                    'class' => 'coregBox sms-coreg-checkbox',
                    'data-name' => 'birddog-sms'
                ]);
            }

            $data = [
                'text' => $this->getText().$idInput,
                'extraFields' => '',
                'checkboxField' => \Form::checkbox(sprintf('coregs[%s][checked]', $name), 1, false, [
                    'class' => 'coregBox',
                    'data-name' => 'birddog'
                ]),
                'extraCheckbox' => $extraCheckbox
            ];

            if(!empty($extraFields)){
                $data['extraFields'] = implode($extraFields, '');
            }
            return \View::make("includes.coreg." . strtolower($this->getName()), $data);
        }
        return "";
    }

    public function getJs()
    {
        if (\View::exists("includes.coreg.js." . strtolower($this->getName()))) {
            return \View::make("includes.coreg.js." . strtolower($this->getName()));
        }
        return "";
    }

    /**
     * Called on json_encode
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'position' => $this->getDisplayPosition(),
            'isVisible' => $this->getVisible(),
            'text' => $this->getText(),
            'monthlyCap' => $this->getMonthlyCap(),
            'html' => $this->getHtml(),
            'js' => $this->getJs(),
            'extra' => json_decode($this->getExtra()) ?: []
        ];
    }
}
