<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PushNotifications
 *
 * @ORM\Table(name="push_notifications", uniqueConstraints={@ORM\UniqueConstraint(name="push_notifications_slug_unique", columns={"slug"})})
 * @ORM\Entity
 */
class PushNotifications
{
    const LONG_TIME_NO_SEE = 'long-time-no-see';
    const NEW_EMAIL = 'new-email';
    const NEW_SCHOLARSHIP_MATCHES = 'new-scholarship-matches';
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     */
    private $slug;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }
}

