<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferralAward extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data to sent in email, which passed to a view file
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $attachmentPath;

    /**
     * @var string
     */
    protected $attachmentName;

    /**
     * @var string
     */
    protected $attachmentMime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data, $attachmentPath, $attachmentName, $attachmentMime = 'text/csv')
    {
        $this->config = \Config::get("scholarshipowl.mail.system.referral_award");
        $this->data = $data;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentName = $attachmentName;
        $this->attachmentMime = $attachmentMime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->config['to'])
            ->from($this->config['from'])
            ->subject($this->config['subject'])
            ->view('emails.system.referral_award')
            ->with($this->data)
            ->attach($this->attachmentPath, [
                'as' => $this->attachmentName,
                'mime' => $this->attachmentMime,
            ]);
    }
}
