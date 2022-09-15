<?php namespace App\Jobs;

use App\Services\MandrillEmailService;

class MandrillBulkTemplateJob extends Job
{
    protected $template;
    protected $accountsIds;
    protected $data;
    /**
     * @param string $template
     * @param int    $accountsIds
     * @param array  $data
     */
    public static function dispatch($template, $accountsIds, $data = array())
    {
        dispatch(new static($template, $accountsIds, $data));
    }

    /**
     * MandrillTemplateJob constructor.
     *
     * @param string $template
     * @param int    $accountsIds
     * @param array  $data
     */
    public function __construct($template, $accountsIds, $data = array())
    {
        $this->template = $template;
        $this->accountsIds = $accountsIds;
        $this->data = $data;
    }

    /**
     * Send mandrill template
     * @param MandrillEmailService $emailService
     */
    public function handle(MandrillEmailService $emailService)
    {
        $emailService->sendBulkTemplates($this->template, $this->accountsIds, $this->data);
    }
}
