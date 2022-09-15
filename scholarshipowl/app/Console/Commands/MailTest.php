<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class MailTest extends Command
{

    const ARGUMENT_EMAIL = 'e-mail';

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mail:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send test message to some e-mail for testing.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $to = $this->argument(self::ARGUMENT_EMAIL);

        \Mail::send(
            array('html' => 'emails.system.test.command'),
            array(
                'now' => date('Y/m/d H:i:s'),
            ),
            function(Message $message) use ($to) {
                $message->from("test@scholarshipowl.com", "ScholarshipOwl App Testing");
                $message->subject("Testing message.");
                $message->to($to);
            }
        );
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array(self::ARGUMENT_EMAIL, InputArgument::REQUIRED, 'E-Mail to send message.'),
		);
	}

}
