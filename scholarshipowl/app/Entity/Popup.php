<?php

namespace App\Entity;

use App\Entity\Marketing\RedirectRulesSet;
use Doctrine\ORM\Mapping as ORM;

/**
 * Popup
 *
 * @ORM\Table(name="popup", indexes={@ORM\Index(name="fk_redirect_rules_set_id", columns={"rule_set_id"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\PopupRepository")
 */
class Popup
{
    const POPUP_TYPE_RAF = "raf";
    const POPUP_TYPE_POPUP = 'popup';
    const POPUP_TYPE_MISSION = "mission";
    const POPUP_TYPE_PACKAGE= "package";

    const POPUP_DISPLAY_NONE = 0;
    const POPUP_DISPLAY_BEFORE = 1;
    const POPUP_DISPLAY_AFTER = 2;
    const POPUP_DISPLAY_BOTH = 3;
    const POPUP_DISPLAY_EXIT = 4;

    /**
     * @var integer
     *
     * @ORM\Column(name="popup_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $popupId;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_display", type="string", nullable=false)
     */
    private $popupDisplay = self::POPUP_DISPLAY_NONE;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_title", type="string", length=255, nullable=true)
     */
    private $popupTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_text", type="text", length=65535, nullable=true)
     */
    private $popupText;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_type", type="string", length=255, nullable=true)
     */
    private $popupType = self::POPUP_TYPE_POPUP;

    /**
     * @var integer
     *
     * @ORM\Column(name="popup_target_id", type="integer", nullable=true)
     */
    private $popupTargetId;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_cms_ids", type="string", length=255, nullable=true)
     */
    private $popupCmsIds;

    /**
     * @var integer
     *
     * @ORM\Column(name="popup_delay", type="integer", nullable=true)
     */
    private $popupDelay = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="popup_display_times", type="integer", nullable=true)
     */
    private $popupDisplayTimes = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="trigger_upgrade", type="smallint", nullable=true)
     */
    private $triggerUpgrade = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="popup_exit_dialogue_text", type="string", length=255, nullable=true)
     */
    private $popupExitDialogueText;

    /**
     * @var RedirectRulesSet
     *
     * @ORM\ManyToOne(targetEntity="\App\Entity\Marketing\RedirectRulesSet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rule_set_id", referencedColumnName="redirect_rules_set_id")
     * })
     */
    private $ruleSet;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority = 0;
    /**
     * Popup constructor.
     *
     * @param string    $popupDisplay
     * @param string    $popupTitle
     * @param string    $popupText
     * @param string    $popupType
     * @param int       $popupTargetId
     * @param int       $popupDelay
     * @param int       $popupDisplayTimes
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     */
    public function __construct($popupDisplay, $popupTitle, $popupText,
        $popupType, $popupTargetId, $popupDelay,
        $popupDisplayTimes, \DateTime $startDate, \DateTime $endDate
    ) {
        $this->popupDisplay = $popupDisplay;
        $this->popupTitle = $popupTitle;
        $this->popupText = $popupText;
        $this->popupType = $popupType;
        $this->popupTargetId = $popupTargetId;
        $this->popupDelay = $popupDelay;
        $this->popupDisplayTimes = $popupDisplayTimes;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @param int $triggerUpgrade
     *
     * @return $this
     */
    public function setTriggerUpgrade($triggerUpgrade)
    {
        $this->triggerUpgrade = $triggerUpgrade;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupCmsIds()
    {
        return $this->popupCmsIds;
    }

    /**
     * @param string $popupCmsIds
     *
     * @return $this
     */
    public function setPopupCmsIds($popupCmsIds)
    {
        $this->popupCmsIds = $popupCmsIds;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopupId()
    {
        return $this->popupId;
    }

    /**
     * @return int
     */
    public function getPopupDisplay()
    {
        return $this->popupDisplay;
    }

    /**
     * @param int $popupDisplay
     *
     * @return $this
     */
    public function setPopupDisplay($popupDisplay)
    {
        $this->popupDisplay = $popupDisplay;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupTitle()
    {
        return $this->popupTitle;
    }

    /**
     * @param string $popupTitle
     *
     * @return $this
     */
    public function setPopupTitle($popupTitle)
    {
        $this->popupTitle = $popupTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupText()
    {
        return $this->popupText;
    }

    /**
     * @param string $popupText
     *
     * @return $this
     */
    public function setPopupText($popupText)
    {
        $this->popupText = $popupText;
        return $this;
    }

    /**
     * @return string
     */
    public function getPopupType()
    {
        return $this->popupType;
    }

    /**
     * @param string $popupType
     *
     * @return $this
     */
    public function setPopupType($popupType)
    {
        $this->popupType = $popupType;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopupTargetId()
    {
        return $this->popupTargetId;
    }

    /**
     * @param int $popupTargetId
     *
     * @return $this
     */
    public function setPopupTargetId($popupTargetId)
    {
        $this->popupTargetId = $popupTargetId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopupDelay()
    {
        return $this->popupDelay;
    }

    /**
     * @param int $popupDelay
     *
     * @return $this
     */
    public function setPopupDelay($popupDelay)
    {
        $this->popupDelay = $popupDelay;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopupDisplayTimes()
    {
        return $this->popupDisplayTimes;
    }

    /**
     * @param int $popupDisplayTimes
     *
     * @return $this
     */
    public function setPopupDisplayTimes($popupDisplayTimes)
    {
        $this->popupDisplayTimes = $popupDisplayTimes;
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return int
     */
    public function isTriggerUpgrade()
    {
        return $this->triggerUpgrade;
    }

    /**
     * @return string
     */
    public function getPopupExitDialogueText()
    {
        return $this->popupExitDialogueText;
    }

    /**
     * @param string $popupExitDialogueText
     *
     * @return $this
     */
    public function setPopupExitDialogueText($popupExitDialogueText)
    {
        $this->popupExitDialogueText = $popupExitDialogueText;
        return $this;
    }

    /**
     * Return current \RedirectRulesSet or null if not set
     *
     * @return mixed
     */
    public function getRuleSet()
    {
        return $this->ruleSet;
    }

    /**
     * @param \RedirectRulesSet $ruleSet
     *
     * @return $this
     */
    public function setRuleSet($ruleSet)
    {
        $this->ruleSet = $ruleSet;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
        return $this;

    }

}

