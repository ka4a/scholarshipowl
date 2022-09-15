<?php

namespace App\Entity\Marketing;

use App\Entity\Account;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * Submissions
 *
 * @ORM\Table(name="submission")
 * @ORM\Entity
 */
class Submission
{
    use Timestamps;

    const NAME_TOLUNA = "Toluna";
    const NAME_ACADEMIX = "Academix";
    const NAME_LOAN = "Loan";
    const NAME_ACADEMIX_AGED = "AcademixAged";
    const NAME_BERECRUITED = "Berecruited";
    const NAME_CAPPEX = "Cappex";
    const NAME_DANE_MEDIA = "DaneMedia";
    const NAME_OPINION_OUTPOST = "OpinionOutpost";
    const NAME_SIMPLE_TUITION = "SimpleTuition";
    const NAME_ZU_USA = "ZuUsa";
    const NAME_WAY_UP = "WayUp";
    const NAME_CWL = "Cwl";
    const NAME_DOUBLE_POSITIVE = "DoublePositive";
    const NAME_CHRISTIAN_CONNECTOR = "ChristianConnector";
    const NAME_COLLEGE_EXPRESS = "CollegeExpress";
    const NAME_ZIPRECRUITER = "Ziprecruiter";
    const NAME_VINYL = "Vinyl";
    const NAME_BIRDDOG = "Birddog";
    const NAME_INBOXDOLLARS = "InboxDollars";
    const NAME_ISAY = "ISay";

    const NAME_GOSSAMERSCIENCE = "GossamerScience";

    const NAME_CAPPEXDATADEAL = "CappexDataDeal";

    const STATUS_INVALID = "invalid";
    const STATUS_INCOMPLETE = "incomplete";
    const STATUS_PENDING = "pending";
    const STATUS_SUCCESS = "success";
    const STATUS_ERROR = "error";
    const STATUS_ERROR_VALIDATION = "error_validation";
    const STATUS_ERROR_SUBMISSION = "error_submission";

    /**
     * @var integer
     *
     * @ORM\Column(name="submission_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $submissionId;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="submissions")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=2045, nullable=true)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=2045, nullable=true)
     */
    private $response;

    /**
     * @var string
     *
     * @ORM\Column(name="send_date", type="datetime", nullable=true)
     */
    private $sendDate;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;

    /**
     * @var CoregPlugin
     *
     * @ORM\OneToOne(targetEntity="CoregPlugin", fetch="LAZY")
     * @ORM\JoinColumn(name="coreg_plugin_id", referencedColumnName="coreg_plugin_id", nullable=true)
     */
    private $coregPlugin;

    /**
     * @var SubmissionSources
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Marketing\SubmissionSources", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="source", referencedColumnName="id", nullable=true)
     * })
     */
    private $source;

    /**
     * @return int
     */
    public function getSubmissionId(): int
    {
        return $this->submissionId;
    }

    /**
     * @param int $submissionId
     */
    public function setSubmissionId(int $submissionId)
    {
        $this->submissionId = $submissionId;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response ? : "";
    }

    /**
     * @param string $response
     */
    public function setResponse(string $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getSendDate(): string
    {
        return $this->sendDate instanceof \DateTime ? Carbon::instance($this->sendDate)->toDateTimeString() : $this->sendDate ? : "";
    }

    /**
     * @param string $sendDate
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * @return string
     */
    public function getParams(): string
    {
        return $this->params;
    }

    /**
     * @param string $params
     */
    public function setParams(string $params)
    {
        $this->params = $params;
    }

    /**
     * @return CoregPlugin|null
     */
    public function getCoregPlugin()
    {
        return $this->coregPlugin;
    }

    /**
     * @param CoregPlugin $coregPlugin
     */
    public function setCoregPlugin(CoregPlugin $coregPlugin)
    {
        $this->coregPlugin = $coregPlugin;
    }

    /**
     * @return SubmissionSources|null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param $source
     *
     * @return Submission
     */
    public function setSource( $source)
    {
        $this->source = $source;

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
            self::NAME_BIRDDOG => "Birddog",
            self::NAME_INBOXDOLLARS => "Inbox Dollars",
            self::NAME_ISAY => "ISay",
            self::NAME_GOSSAMERSCIENCE => "GossamerScience",
        );
    }

    public static function getStatuses()
    {
        return array(
            self::STATUS_INCOMPLETE => "Inactive",
            self::STATUS_PENDING => "Pending",
            self::STATUS_SUCCESS => "Success",
            self::STATUS_ERROR => "Error"
        );
    }
}