<?php

namespace App\Events\Scholarship;

use App\Entity\Application;
use App\Entity\Scholarship;
use Illuminate\Support\Facades\Event;

/**
 * Event happens when potential winner chosen
 *
 * Class ScholarshipPotentialWinner
 * @package App\Events\Scholarship
 */
class ScholarshipPotentialWinnerEvent extends Event
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var string|int
     */
    protected $externalApplicationId;

    /**
     * @var Scholarship
     */
    protected $scholarship;

    /**
     * @var int
     */
    protected $scholarshipId;


    public function __construct(Scholarship $scholarship, Application $application)
    {
        $this->scholarship = $scholarship;
        $this->scholarshipId = $scholarship->getScholarshipId();
        $this->application = $application;
        $this->externalApplicationId = $application->getExternalApplicationId();
    }

    /**
     * @return Scholarship
     */
    public function getScholarship()
    {
        return $this->scholarship;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    public function __sleep()
    {
        $this->scholarship = null;
        $this->application = null;

        return ['scholarshipId', 'externalApplicationId'];
    }

    public function __wakeup()
    {
        $this->scholarship = \EntityManager::getRepository(Scholarship::class)
            ->findOneBy(['scholarshipId' => $this->scholarshipId]);

        $this->application = \EntityManager::getRepository(Application::class)
            ->findOneBy(['externalApplicationId' => $this->externalApplicationId]);
    }

}
