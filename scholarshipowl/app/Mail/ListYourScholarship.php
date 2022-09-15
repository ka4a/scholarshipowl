<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ListYourScholarship extends Mailable
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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->config = \Config::get("scholarshipowl.mail.system.list_your_scholarship");
        $this->data = $data;
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
            ->replyTo($this->data['email'])
            ->subject($this->config['subject'])
            ->view('emails.system.list_your_scholarship')
            ->with($this->data);
    }
}
