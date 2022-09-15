<?php namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\DictionaryEntity;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

/**
 * Class ScholarshipStatus
 * @ORM\Entity()
 */
class ApplicationStatus implements JsonApiResource
{
    use DictionaryEntity;

    /**
     * @return string
     */
    public static function getResourceKey()
    {
        return 'application_status';
    }

    const RECEIVED = 'received';
    const REVIEW = 'review';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';

    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=16)
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var array
     * @ORM\OneToMany(targetEntity="Application", mappedBy="status", fetch="EXTRA_LAZY")
     */
    protected $applications;
}
