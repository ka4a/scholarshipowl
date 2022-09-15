<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Kipping values if tutorials where shown for user in admin.
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class UserTutorial implements JsonApiResource
{
    /**
     * @return string
     */
    static function getResourceKey()
    {
        return 'user_tutorial';
    }

    /**
     * @var User
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     */
    protected $user;

    /**
     * First step highlight the "New Scholarship" button.
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $newScholarship = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->getUser()->getId();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewScholarship()
    {
        return $this->newScholarship;
    }

    /**
     * @param bool $newScholarship
     * @return $this
     */
    public function setNewScholarship($newScholarship)
    {
        $this->newScholarship = $newScholarship;
        return $this;
    }
}
