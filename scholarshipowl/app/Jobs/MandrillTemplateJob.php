<?php namespace App\Jobs;

use App\Services\MandrillEmailService;

class MandrillTemplateJob extends Job
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $from;

    /**
     * @param string $template
     * @param int    $accountId
     * @param array  $data
     * @param string $to
     * @param string $subject
     * @param string $from
     */
    public static function dispatch($template, $accountId, $data = array(), $to = null, $subject = null, $from = null)
    {
        dispatch(new static($template, $accountId, $data, $to, $subject, $from));
    }

    /**
     * MandrillTemplateJob constructor.
     *
     * @param string $template
     * @param int    $accountId
     * @param array  $data
     * @param string $to
     * @param string $subject
     * @param string $from
     */
    public function __construct($template, $accountId, $data = array(), $to = null, $subject = null, $from = null)
    {
        $this->template = $template;
        $this->accountId = $accountId;
        $this->data = $data;
        $this->to = $to;
        $this->subject = $subject;
        $this->from = $from;
    }

    /**
     * Send mandrill template
     * @param MandrillEmailService $mailService
     */
    public function handle(MandrillEmailService $mailService)
    {
        $mailService->sendTemplate( $this->template,
            $this->accountId,
            $this->data,
            $this->to,
            $this->subject,
            $this->from
        );
    }
}
