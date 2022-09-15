<?php

namespace App\Console\Commands;

use App\Mail\TestEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test { email : Provide email for sending test email }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for testing email configurations.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Mail::to($this->argument('email'))->send(new TestEmail());
    }
}
