<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SocialAccounts
 *
 * @ORM\Table(name="winner")
 * @ORM\Entity
 */
class Winner implements \JsonSerializable
{

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Scholarship
     *
     * @ORM\OneToOne(targetEntity="Scholarship", fetch="LAZY")
     * @ORM\JoinColumn(name="scholarship_id", referencedColumnName="scholarship_id")
     */
    private $scholarship;

    /**
     * @var string
     *
     * @ORM\Column(name="scholarship_title", type="string")
     */
    private $scholarshipTitle;

    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="Account", fetch="LAZY")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     */
    private $account;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="won_at", type="datetime", nullable=false)
     */
    private $wonAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_won", type="integer", nullable=false)
     */
    private $amountWon;

    /**
     * @var string
     *
     * @ORM\Column(name="winner_name", type="string", nullable=false)
     */
    private $winnerName;

    /**
     * @var string
     *
     * @ORM\Column(name="winner_photo", type="string", nullable=false)
     */
    private $winnerPhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="testimonial_text", type="string", nullable=false)
     */
    private $testimonialText;

    /**
     * @var string
     *
     * @ORM\Column(name="testimonial_video", type="string", nullable=true)
     */
    private $testimonialVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="published", type="integer")
     */
    private $published = 0;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Scholarship $scholarship
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship)
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    /**
     * @return Scholarship|null
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @return int|null
     */
    public function getScholarshipId()
    {
        return $this->scholarship ? $this->scholarship->getScholarshipId() : null;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setScholarshipTitle(string $value)
    {
        $this->scholarshipTitle = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getScholarshipTitle()
    {
        return $this->scholarshipTitle;
    }

    /**
     * @param Account $account
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return int|null
     */
    public function getAccountId()
    {
        return $this->account ? $this->account->getAccountId() : null;
    }

    /**
     * @param \DateTime $wonAt
     * @return $this
     */
    public function setWonAt(\DateTime $wonAt)
    {
        $this->wonAt = $wonAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getWonAt()
    {
        return $this->wonAt;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setAmountWon(int $value)
    {
        $this->amountWon = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmountWon()
    {
        return $this->amountWon;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setWinnerName(string $value)
    {
        $this->winnerName = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getWinnerName()
    {
        return $this->winnerName;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setWinnerPhoto(string $value)
    {
        $this->winnerPhoto = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getWinnerPhoto()
    {
        return $this->winnerPhoto;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTestimonialText(string $value)
    {
        $this->testimonialText = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTestimonialText()
    {
        return $this->testimonialText;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTestimonialVideo(string $value)
    {
        $this->testimonialVideo = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTestimonialVideo()
    {
        return $this->testimonialVideo;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setPublished(bool $value)
    {
        $this->published = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
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
            'scholarshipTitle' => $this->getScholarshipTitle(),
            'wonAt' => $this->getWonAt(),
            'amountWon' => $this->getAmountWon(),
            'winnerName' => $this->getWinnerName(),
            'winnerPhoto' => $this->getWinnerPhoto(),
            'testimonialText' => $this->getTestimonialText(),
            'testimonialVideo' => $this->getTestimonialVideo()
        ];
    }
}
