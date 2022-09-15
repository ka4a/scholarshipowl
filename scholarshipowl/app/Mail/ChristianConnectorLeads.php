<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChristianConnectorLeads extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Data to sent in email, which passed to a view file
     * @var array
     */
    protected $data;

    protected $config;

    /**
     * ChristianConnectorLeads constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->data['to'])
            ->from($this->data['from']['address'], $this->data['from']['name'])
            ->subject($this->data['subject'])
            ->view("emails.system.cron.simple-tuition-email")
            ->attach($this->data['attach']['file'], ['as' => $this->data['attach']['name']]);
    }
}
