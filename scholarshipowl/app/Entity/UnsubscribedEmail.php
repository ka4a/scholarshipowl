<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnsubscribedEmail
 *
 * @ORM\Table(name="unsubscribed_email", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unsubscribed_email_email_unique", columns={"email"})
 * })
 * @ORM\Entity
 */
class UnsubscribedEmail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="unsubscribed_email_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $unsubscribedEmailId;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * UnsubscribedEmail constructor.
     *
     * @param string $email
     */
    public function __construct($email)
    {
        $this->setEmail($email);
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * Get unsubscribedEmailId
     *
     * @return integer
     */
    public function getUnsubscribedEmailId()
    {
        return $this->unsubscribedEmailId;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return UnsubscribedEmail
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UnsubscribedEmail
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return UnsubscribedEmail
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}

