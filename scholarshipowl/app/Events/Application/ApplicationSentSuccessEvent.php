<?php

namespace App\Events\Application;

use App\Entity\Account;
use App\Entity\Application;
use App\Events\Event;

class ApplicationSentSuccessEvent extends Event
{

    /**
     * @var Application
     */
    protected $application;


    /**
     * @var mixed
     */
    protected $senderResponse;
    /**
     * ApplicationSentEvent constructor.
     * @param Application $application
     */
    public function __construct(Application $application, $senderResponse = null)
    {
        $this->application = $application;
        $this->senderResponse = $senderResponse;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return mixed|null
     */
    public function getSenderResponse()
    {
        return $this->senderResponse;
    }

}
