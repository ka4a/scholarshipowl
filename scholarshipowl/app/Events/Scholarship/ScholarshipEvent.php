<?php namespace App\Events\Scholarship;

use App\Entity\Scholarship;

class ScholarshipEvent
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Scholarship
     */
    protected $scholarship;

    /**
     * ScholarshipEvent constructor.
     *
     * @param Scholarship|int $scholarship
     */
    public function __construct($scholarship)
    {
        $this->id = $scholarship;

        if ($scholarship instanceof Scholarship) {
            $this->scholarship = $scholarship;
            $this->id = $scholarship->getScholarshipId();
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        if ($this->scholarship === null) {
            $this->scholarship = \EntityManager::find(Scholarship::class, $this->id);
        }

        return $this->scholarship;
    }

    /**
     * Clear scholarship entity on serialize.
     */
    public function __sleep()
    {
        $this->scholarship = null;

        return ['id'];
    }
}
